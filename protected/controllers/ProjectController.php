<?php

namespace prime\controllers;

use herams\common\models\Permission;
use herams\common\models\Project;
use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\project\Create;
use prime\controllers\project\DeleteWorkspaces;
use prime\controllers\project\Export;
use prime\controllers\project\ExportDashboard;
use prime\controllers\project\ExternalDashboard;
use prime\controllers\project\Filter;
use prime\controllers\project\ImportDashboard;
use prime\controllers\project\Index;
use prime\controllers\project\Pages;
use prime\controllers\project\Pdf;
use prime\controllers\project\RequestAccess;
use prime\controllers\project\Share;
use prime\controllers\project\Update;
use prime\controllers\project\View;
use prime\controllers\project\ViewForSurveyJs;
use prime\controllers\project\Workspaces;
use yii\filters\PageCache;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
                'redirect' => ['/project'],
            ],
            'delete-workspaces' => [
                'class' => DeleteWorkspaces::class,
            ],
            'export-surveyjs' => [
                'class' => Export::class,
            ],
            'export-dashboard' => ExportDashboard::class,
            'external-dashboard' => ExternalDashboard::class,
            'filter' => Filter::class,
            'import-dashboard' => ImportDashboard::class,
            'index' => Index::class,
            'pages' => Pages::class,
            'pdf' => Pdf::class,
            'request-access' => RequestAccess::class,
            'share' => Share::class,
            'update' => Update::class,
            'view' => ViewForSurveyJs::class,
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
                        'filter' => ['post'],
                    ],
                ],

                'pageCache' => [
                    'class' => PageCache::class,
                    'enabled' => ! YII_DEBUG,
                    'only' => ['summary'],
                    'variations' => [
                        \Yii::$app->request->getQueryParam('id'),
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
                                'create',
                            ],
                            'roles' => [Permission::PERMISSION_CREATE_PROJECT],
                        ],
                    ],
                ],
            ]
        );
    }
}
