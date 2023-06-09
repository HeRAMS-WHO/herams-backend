<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\repositories\FormRepository;
use yii\base\Action;

class Update extends Action
{
    public function run(
        FormRepository $formRepository,
        WorkspaceRepository $workspaceRepository,
        BreadcrumbService $breadcrumbService,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspaceId = new WorkspaceId($id);
        $this->controller->view->breadcrumbCollection->mergeWith($breadcrumbService->retrieveForWorkspace($workspaceId));
        return $this->controller->render('update', [
            'form' => $formRepository->getUpdateWorkspaceForm($workspaceId, $workspaceRepository->getProjectId($workspaceId)),
            'workspaceId' => $workspaceId,
        ]);
    }
}
