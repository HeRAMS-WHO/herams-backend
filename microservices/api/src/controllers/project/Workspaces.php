<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\ProjectId;
use yii\base\Action;

class Workspaces extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        $projectId = new ProjectId($id);

        $workspaces = $workspaceRepository->retrieveForProject($projectId);
        $workspaceToArray = [];
        foreach ($workspaces as $workspace) {
            $tempWorkspace = $workspace->toArray();
            if (is_null($tempWorkspace['date_of_update'])) {
                $tempWorkspace['date_of_update'] = '0000-00-00';
            }
            $workspaceToArray[] = $tempWorkspace;
        }
        return $workspaceToArray;
    }
}
