<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\ProjectId;
use prime\components\Controller;
use yii\base\Action;

class Workspaces extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        $projectId = new ProjectId($id);

        $workspaces = $workspaceRepository->retrieveForProject($projectId);
        return $this->controller->asJson($workspaces);
    }
}
