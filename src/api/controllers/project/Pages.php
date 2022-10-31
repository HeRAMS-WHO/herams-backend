<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use prime\components\Controller;
use prime\repositories\PageRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use yii\base\Action;

class Pages extends Action
{
    public function run(
        PageRepository $pageRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $projectId = new ProjectId($id);

        $workspaces = $pageRepository->retrieveForProject($projectId);
        return $this->controller->asJson($workspaces);
    }
}
