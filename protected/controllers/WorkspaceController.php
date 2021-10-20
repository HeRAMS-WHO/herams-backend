<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\actions\DeleteAction;
use prime\actions\ExportAction;
use prime\components\Controller;
use prime\controllers\workspace\Create;
use prime\controllers\workspace\Facilities;
use prime\controllers\workspace\Import;
use prime\controllers\workspace\Refresh;
use prime\controllers\workspace\RequestAccess;
use prime\controllers\workspace\Responses;
use prime\controllers\workspace\Share;
use prime\controllers\workspace\Update;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\objects\Breadcrumb;
use prime\queries\ResponseForLimesurveyQuery;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\User;

class WorkspaceController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;
    public $defaultAction = 'facilities';

    public function __construct(
        $id,
        $module,
        private ProjectRepository $projectRepository,
        private WorkspaceRepository $workspaceRepository,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'responses' => Responses::class,
            'export' => [
                'class' => ExportAction::class,
                'subject' => static function (Request $request) {
                      return WorkspaceForLimesurvey::findOne(['id' => $request->getQueryParam('id')]);
                },
                'responseQuery' => static function (WorkspaceForLimesurvey $workspace): ResponseForLimesurveyQuery {
                    return $workspace->getResponses();
                },
                'surveyFinder' => function (WorkspaceForLimesurvey $workspace) {
                    return $workspace->project->getSurvey();
                },
                'checkAccess' => function (WorkspaceForLimesurvey $workspace, User $user) {
                    return $user->can(Permission::PERMISSION_EXPORT, $workspace);
                }
            ],
            'facilities' => Facilities::class,
            'update' => Update::class,
            'create' => Create::class,
            'share' => Share::class,
            'import' => Import::class,
            'refresh' => Refresh::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => WorkspaceForLimesurvey::find(),
                'redirect' => function (WorkspaceForLimesurvey $workspace) {
                    return ['/project/workspaces', 'id' => $workspace->project_id];
                }
            ],
            'request-access' => RequestAccess::class,
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verb' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'create' => ['get', 'post']
                    ]
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ],
            ]
        );
    }

    /**
     * Inject the model for the tab menu into the view.
     */
    public function render($view, $params = [])
    {
        $breadcrumbCollection = $this->view->getBreadcrumbCollection()
            ->add((new Breadcrumb())->setUrl(['/project/index'])->setLabel(\Yii::t('app', 'Projects')))
        ;

        if (in_array($this->action->id, ['create', 'import']) && $projectId = (int) $this->request->getQueryParam('project_id')) {
            $project = $this->projectRepository->retrieveForBreadcrumb(new ProjectId($projectId));
            $breadcrumbCollection->add($project);
        } elseif ($id = $this->request->getQueryParam('id')) {
            $model = $this->workspaceRepository->retrieveForBreadcrumb(new WorkspaceId((int) $id));
            $project = $this->projectRepository->retrieveForBreadcrumb($model->getProjectId());
            $breadcrumbCollection->add($project);
        }

        if (!isset($params['tabMenuModel']) && $this->request->getQueryParam('id')) {
            $workspaceId = new WorkspaceId((int) $this->request->getQueryParam('id'));
            $params['tabMenuModel'] = $this->workspaceRepository->retrieveForTabMenu($workspaceId);
        }
        return parent::render($view, $params);
    }
}
