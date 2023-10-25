<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\domain\project\ProjectRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\models\UserRole;
use herams\common\values\ProjectId;
use herams\common\values\userRole\UserRoleId;
use herams\common\values\userRole\UserRoleTargetEnum;
use InvalidArgumentException;

final class UserRoleRepository
{
    /**
     * UserRoleRepository constructor.
     *
     * @param  ModelHydrator  $modelHydrator
     */
    public function __construct(
        private ModelHydrator $modelHydrator,
        private ProjectRepository $projectRepository,
    ) {
    }

    /**
     * @param  UserRoleRequest  $userRoleRequest
     *
     * @return UserRoleId
     * @throws InvalidArgumentException
     */
    public function create(UserRoleRequest $userRoleRequest): UserRoleId
    {
        $record = new UserRole();
        $this->modelHydrator->hydrateActiveRecord($userRoleRequest, $record);
        if (!$record->save()) {
            throw new InvalidArgumentException(
                'Validation failed: '.print_r($record->errors, true)
            );
        }
        return new UserRoleId($record->id);
    }

    /**
     * @param  ProjectId  $projectId
     *
     * @return array
     */
    public function retrieveUserRolesInProject(ProjectId $projectId): array
    {
        $workspaces = $this->projectRepository->retrieveById(
            $projectId
        )->workspaces;
        $workspacesIds = [];
        foreach ($workspaces as $workspace) {
            $workspacesIds[] = $workspace->id;
        }
        return UserRole::find()
            ->where([
                'target'    => UserRoleTargetEnum::project->value,
                'target_id' => $projectId->getValue(),
            ])
            ->orWhere([
                'target'    => UserRoleTargetEnum::workspace->value,
                'target_id' => $workspacesIds,
            ])
            ->all();
    }
}
