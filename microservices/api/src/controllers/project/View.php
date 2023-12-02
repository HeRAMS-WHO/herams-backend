<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\userRole\UserRoleRepository;
use herams\common\enums\ProjectVisibility;
use herams\common\values\ProjectId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        UserRoleRepository $userRoleRepository,
        int $id
    ): array {
        $project = $projectRepository->getProject(new ProjectId($id));
        $visibility = $project->visibility;
        $project->visibility = ProjectVisibility::from(strtolower($visibility))->label();
        return [...$project->toArray(), 'projectUsersCount' => $userRoleRepository->countUsersInProject(new ProjectId($id))];
    }
}
