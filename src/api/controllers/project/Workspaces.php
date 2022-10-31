<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use prime\components\Controller;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use yii\base\Action;

class Workspaces extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $projectId = new ProjectId($id);

        $workspaces = $workspaceRepository->retrieveForProject($projectId);
        return $this->controller->asJson($workspaces);
    }
}
