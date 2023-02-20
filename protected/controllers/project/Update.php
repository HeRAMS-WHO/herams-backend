<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\repositories\FormRepository;
use yii\base\Action;
use function iter\toArray;

class Update extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        BreadcrumbService $breadcrumbService,
        FormRepository $formRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $projectId = new ProjectId($id);

        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );

        $project = $projectRepository->retrieveForRead($projectId);
        return $this->controller->render('update', [
            'project' => $project,
            'form' => $formRepository->getUpdateProjectForm($projectId),
            'projectId' => $projectId,
        ]);
    }
}
