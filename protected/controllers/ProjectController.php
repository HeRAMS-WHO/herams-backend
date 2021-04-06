<?php

namespace prime\controllers;

use prime\actions\DeleteAction;
use prime\actions\ExportAction;
use prime\components\Controller;
use prime\controllers\project\Create;
use prime\controllers\project\DeleteWorkspaces;
use prime\controllers\project\ExportDashboard;
use prime\controllers\project\ExternalDashboard;
use prime\controllers\project\Filter;
use prime\controllers\project\ImportDashboard;
use prime\controllers\project\Index;
use prime\controllers\project\Pages;
use prime\controllers\project\Pdf;
use prime\controllers\project\Share;
use prime\controllers\project\SyncWorkspaces;
use prime\controllers\project\Update;
use prime\controllers\project\View;
use prime\controllers\project\Workspaces;
use prime\controllers\project\Limesurvey;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Response;
use prime\queries\ResponseQuery;
use yii\filters\PageCache;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\User;

class ProjectController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function actions()
    {
        return [
            'export-dashboard' => ExportDashboard::class,
            'import-dashboard' => ImportDashboard::class,
            'external-dashboard' => ExternalDashboard::class,
            'filter' => Filter::class,
            'create' => Create::class,
            'update' => Update::class,
            'index' => Index::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Project::find(),
                'redirect' => ['/project']
            ],
            'delete-workspaces' => [
                'class' => DeleteWorkspaces::class
            ],
            'sync-workspaces' => SyncWorkspaces::class,
            'view' => View::class,
            'limesurvey' => Limesurvey::class,
            'pdf' => Pdf::class,
            'share' => Share::class,
            'workspaces' => Workspaces::class,
            'pages' => Pages::class,
            'export' => [
                'class' => ExportAction::class,
                'subject' => static function (Request $request) {
                    return Project::findOne(['id' => $request->getQueryParam('id')]);
                },
                'responseQuery' => static function (Project $project): ResponseQuery {
                    return Response::find()->project($project)->with('workspace');
                },
                'surveyFinder' => function (Project $project) {
                    return $project->getSurvey();
                },
                'checkAccess' => function (Project $project, User $user) {
                    return $user->can(Permission::PERMISSION_EXPORT, $project);
                }
            ],
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['delete'],
                        'filter' => ['post']
                    ]
                ],

                'pageCache' => [
                    'class' => PageCache::class,
                    'enabled' => !YII_DEBUG,
                    'only' => ['summary'],
                    'variations' => [
                        \Yii::$app->request->getQueryParam('id')
                    ],
                    'duration' => 120,
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'share',
                                'view',
                                'pdf',
                                'limesurvey',
                                'summary',
                                'index',
                                'update',
                                'workspaces',
                                'delete',
                                'check',
                                'pages',
                                'filter',
                                'export-dashboard',
                                'import-dashboard',
                                'export',
                                'external-dashboard'
                            ],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'create'
                            ],
                            'roles' => [Permission::PERMISSION_CREATE_PROJECT]
                        ]
                    ],
                ]
            ]
        );
    }
}
