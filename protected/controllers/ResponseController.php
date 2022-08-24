<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\response\Compare;
use prime\controllers\response\SurveyJs;
use prime\controllers\response\Update;
use prime\controllers\response\View;
use prime\objects\Breadcrumb;
use prime\repositories\ProjectRepository;
use prime\repositories\ResponseForLimesurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ResponseId;
use yii\helpers\Url;

class ResponseController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN_TABS;

    public function __construct(
        $id,
        $module,
        private ProjectRepository $projectRepository,
        private ResponseForLimesurveyRepository $responseRepository,
        private WorkspaceRepository $workspaceRepository,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'compare' => Compare::class,
            'update' => Update::class,
            'surveyjs' => SurveyJs::class,
            'view' => View::class

        ];
    }

    public function render($view, $params = [])
    {
        $breadcrumbCollection = $this->view->getBreadcrumbCollection();
        $breadcrumbCollection->add(new Breadcrumb(\Yii::t('app', 'Projects'), Url::to(['/project/index'])));

        if ($id = $this->request->getQueryParam('id')) {
            try {
                $model = $this->responseRepository->retrieveForBreadcrumb(new ResponseId((int)$id));
                $workspace = $this->workspaceRepository->retrieveForBreadcrumb($model->getWorkspaceId());
                $project = $this->projectRepository->retrieveForBreadcrumb($workspace->getProjectId());
                $breadcrumbCollection
                    ->add($project)
                    ->add($workspace);
            } catch (\Throwable $t) {
                \Yii::warning(new \Exception('Error during breadcrumb rendering: ' . $this->route, 0, $t));
            }

        }

        return parent::render($view, $params);
    }
}
