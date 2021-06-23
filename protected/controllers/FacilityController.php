<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\facility\CopyLatestResponse;
use prime\controllers\facility\Create;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;
use prime\controllers\facility\Update;
use prime\objects\Breadcrumb;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\WorkspaceId;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class FacilityController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function __construct(
        $id,
        $module,
        private FacilityRepository $facilityRepository,
        private ProjectRepository $projectRepository,
        private WorkspaceRepository $workspaceRepository,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'create' => Create::class,
            'update' => Update::class,
            'responses' => Responses::class,
            'copy-latest-response' => CopyLatestResponse::class
        ];
    }

    public function beforeAction($action)
    {
        $breadcrumbCollection = $this->view->getBreadcrumbCollection()
            ->add((new Breadcrumb())->setUrl(['/project/index'])->setLabel(\Yii::t('app', 'Projects')));

        if (in_array($action->id, ['create']) && $workspaceId = (int) $this->request->getQueryParam('parent_id')) {
            $workspace = $this->workspaceRepository->retrieveForBreadcrumb(new WorkspaceId($workspaceId));
            $project = $this->projectRepository->retrieveForBreadcrumb($workspace->getProjectId());
            $breadcrumbCollection
                ->add((new Breadcrumb())->setUrl(['/project/workspaces','id' => $project->getId()])->setLabel($project->getTitle()))
                ->add((new Breadcrumb())->setUrl(['/workspace/facilities','id' => $workspace->getId()])->setLabel($workspace->getTitle()));
        } elseif ($id = $this->request->getQueryParam('id')) {
            $facility = $this->facilityRepository->retrieveForBreadcrumb(new FacilityId($id));
            $workspace = $this->workspaceRepository->retrieveForBreadcrumb($facility->getWorkspaceId());
            $project = $this->projectRepository->retrieveForBreadcrumb($workspace->getProjectId());
            $breadcrumbCollection
                ->add((new Breadcrumb())->setUrl(['/project/workspaces','id' => $project->getId()])->setLabel($project->getTitle()))
                ->add((new Breadcrumb())->setUrl(['/workspace/facilities','id' => $workspace->getId()])->setLabel($workspace->getTitle()));
        }

        return parent::beforeAction($action);
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}
