<?php

namespace prime\controllers;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
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
use prime\controllers\project\Update;
use prime\controllers\project\Users;
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
            'users' => Users::class,
            'update' => Update::class,
            'view' => ViewForSurveyJs::class,
            'workspaces' => Workspaces::class,
        ];
    }

    public function actionExport(
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $id,
        bool $answersAsText = true
    ) {
        $projectId = new ProjectId($id);

        $locales = $projectRepository->retrieveProjectLocales($projectId);
        $adminSurveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $dataSurveyId = $projectRepository->retrieveDataSurveyId($projectId);

        $variableSet = $surveyRepository->retrieveVariableSet($adminSurveyId, $dataSurveyId);
        $helper = new \herams\common\helpers\FlattenResponseHelper($variableSet, projectLocales: $locales, answersAsText: $answersAsText);

        /**
         * Configuration that we must support (#6)
         * - Locale, already supported by the helper
         * - Headers: text and / or codes
         * - Encoding answers as labels or codes
         * - Date for the report
         */
        $records = (function () use ($workspaceRepository, $facilityRepository, $projectId) {
            foreach ($workspaceRepository->retrieveForProject($projectId) as $workspace) {
                yield from $facilityRepository->retrieveByWorkspaceId(new WorkspaceId($workspace->id));
            }
        })();

        $result = '<pre>';
        foreach ($helper->flattenAll($records) as $flat) {
            $result .= json_encode($flat, JSON_PRETTY_PRINT);
            $result .= "\n";
        }
        return $this->renderContent($result . '</pre>');
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
                            'roles' => [PermissionOld::PERMISSION_CREATE_PROJECT],
                        ],
                    ],
                ],
            ]
        );
    }
}
