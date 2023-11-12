<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use prime\components\Controller;
use prime\controllers\workspace\Create;
use prime\controllers\workspace\Facilities;
use prime\controllers\workspace\Import;
use prime\controllers\workspace\RequestAccess;
use prime\controllers\workspace\Share;
use prime\controllers\workspace\Update;
use prime\controllers\workspace\Users;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
            'facilities' => Facilities::class,
            'update' => Update::class,
            'create' => Create::class,
            'share' => Share::class,
            'request-access' => RequestAccess::class,
            'users' => Users::class,
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
                        'create' => ['get', 'post'],
                    ],
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Inject the model for the tab menu into the view.
     */
    public function render($view, $params = [])
    {
        //
        //        $this->view->getBreadcrumbCollection()->add(new Breadcrumb(\Yii::t('app', 'Projects'), Url::to(['/project/index'])));
        //
        //        if (in_array($this->action->id, ['create', 'import']) && $projectId = (int) $this->request->getQueryParam('project_id')) {
        //            $project = $this->projectRepository->retrieveForBreadcrumb(new ProjectId($projectId));
        //            $breadcrumbCollection->add($project);
        //        } elseif ($id = $this->request->getQueryParam('id')) {
        //            $model = $this->workspaceRepository->retrieveForBreadcrumb(new WorkspaceId((int) $id));
        //            $project = $this->projectRepository->retrieveForBreadcrumb($model->getProjectId());
        //            $breadcrumbCollection->add($project);
        //        }
        //
        if (! isset($params['tabMenuModel'])
            && $this->request->getQueryParam(
                'id'
            )
        ) {
            $workspaceId = new WorkspaceId(
                (int) $this->request->getQueryParam('id')
            );
            $params['tabMenuModel']
                = $this->workspaceRepository->retrieveForTabMenu($workspaceId);
        }
        return parent::render($view, $params);
    }
}
