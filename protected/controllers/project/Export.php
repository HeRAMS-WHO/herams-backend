<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use herams\common\values\ProjectId;
use prime\actions\FrontendAction;

final class Export extends FrontendAction
{
    public function run(
        ProjectRepository $projectRepository,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $projectId = new ProjectId($id);

        $project = $projectRepository->retrieveForExport($projectId);
        // This is not needed; the repository does the check as well. I'm just not sure where it should happen.
        $accessCheck->requirePermission($project, PermissionOld::PERMISSION_EXPORT);

        return $this->render('export', [
            'projectId' => $projectId,
            'project' => $project,
        ]);
    }
}
