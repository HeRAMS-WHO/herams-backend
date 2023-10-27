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
        $userRolesOfProjects = UserRole::find()
            ->where(
                [
                    'target'    => UserRoleTargetEnum::project->value,
                    'target_id' => $projectId->getValue()
                ]
            )
            ->with([
                'roleInfo'           => fn($query) => $query->select(
                    ['id', 'name']
                ),
                'lastModifiedByInfo' => fn($query) => $query->select(
                    ['id', 'name']
                ),
                'createdByInfo'      => fn($query) => $query->select(
                    ['id', 'name']
                ),
                'userInfo'           => fn($query) => $query->select(
                    ['id', 'name', 'email']
                ),
                'projectInfo'        => fn($query) => $query->select(
                    ['id', 'primary_language', 'i18n']
                ),
            ])
            ->asArray()
            ->all();
        $userRolesOfWorkspaces = UserRole::find()
            ->where(
                [
                    'target'    => UserRoleTargetEnum::workspace->value,
                    'target_id' => $workspacesIds
                ],
            )
            ->with([
                'roleInfo'           => fn($query) => $query->select(
                    ['id', 'name']
                ),
                'lastModifiedByInfo' => fn($query) => $query->select(
                    ['id', 'name']
                ),
                'createdByInfo'      => fn($query) => $query->select(
                    ['id', 'name']
                ),
                'userInfo'           => fn($query) => $query->select(
                    ['id', 'name', 'email']
                ),
                'workspaceInfo'      => fn($query) => $query->select(
                    ['id', 'i18n']
                ),
            ])
            ->asArray()
            ->all();
        foreach ($userRolesOfWorkspaces as &$userRoleOfWorkspace) {
            $userRoleOfWorkspace['projectInfo'] = null;
        }
        foreach ($userRolesOfProjects as &$userRoleOfProject) {
            $userRoleOfProject['workspaceInfo'] = null;
        }
        $userRoles = array_merge($userRolesOfProjects, $userRolesOfWorkspaces);
        usort($userRoles, fn($a, $b) => $a['id'] <=> $b['id']);
        return $userRoles;
    }

    /**
     * @param  UserRoleId  $userRoleId
     *
     * @throws InvalidArgumentException
     */
    public function delete(UserRoleId $userRoleId): void
    {
        $userRole = UserRole::findOne($userRoleId->getValue());
        if ($userRole === null) {
            throw new InvalidArgumentException(
                'User role not found'
            );
        }
        $userRole->delete();
    }
}
