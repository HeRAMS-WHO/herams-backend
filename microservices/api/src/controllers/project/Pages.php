<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\page\PageRepository;
use herams\common\values\ProjectId;
use yii\base\Action;

class Pages extends Action
{
    public function run(
        PageRepository $pageRepository,
        int $id
    ) {
        $projectId = new ProjectId($id);

        $workspaces = $pageRepository->retrieveForProject($projectId);
        return $this->controller->asJson($workspaces);
    }
}
