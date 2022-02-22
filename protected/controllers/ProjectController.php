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
use prime\controllers\project\Limesurvey;
use prime\controllers\project\Pages;
use prime\controllers\project\Pdf;
use prime\controllers\project\RequestAccess;
use prime\controllers\project\Share;
use prime\controllers\project\SyncWorkspaces;
use prime\controllers\project\Update;
use prime\controllers\project\View;
use prime\controllers\project\Workspaces;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\read\Project as ReadProject;
use prime\models\ar\ResponseForLimesurvey;
use prime\objects\Breadcrumb;
use prime\queries\ResponseForLimesurveyQuery;
use yii\filters\PageCache;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\User;

class ProjectController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'create' => Create::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Project::find(),
                'redirect' => ['/project']
            ],
            'delete-workspaces' => [
                'class' => DeleteWorkspaces::class
            ],
            'export' => [
                'class' => ExportAction::class,
                'subject' => static function (Request $request) {
                    return ReadProject::findOne(['id' => $request->getQueryParam('id')]);
                },
                'responseQuery' => static function (Project $project): ResponseForLimesurveyQuery {
                    return ResponseForLimesurvey::find()->project($project)->with('workspace');
                },
                'surveyFinder' => function (Project $project) {
                    return $project->getSurvey();
                },
                'checkAccess' => function (Project $project, User $user) {
                    return $user->can(Permission::PERMISSION_EXPORT, $project);
                }
            ],
            'export-dashboard' => ExportDashboard::class,
            'external-dashboard' => ExternalDashboard::class,
            'filter' => Filter::class,
            'import-dashboard' => ImportDashboard::class,
            'index' => Index::class,
            'limesurvey' => Limesurvey::class,
            'pages' => Pages::class,
            'pdf' => Pdf::class,
            'request-access' => RequestAccess::class,
            'share' => Share::class,
            'sync-workspaces' => SyncWorkspaces::class,
            'update' => Update::class,
            'view' => View::class,
            'workspaces' => Workspaces::class,
        ];
    }

    public function behaviors(): array
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
                                'check',
                                'delete',
                                'export',
                                'export-dashboard',
                                'external-dashboard',
                                'filter',
                                'import-dashboard',
                                'index',
                                'limesurvey',
                                'pages',
                                'pdf',
                                'request-access',
                                'share',
                                'summary',
                                'update',
                                'view',
                                'workspaces',
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

    public function render($view, $params = [])
    {
        $this->view->getBreadcrumbCollection()->add((new Breadcrumb())->setUrl(['/project/index'])->setLabel(\Yii::t('app', 'Projects')));

        return parent::render($view, $params);
    }
}
