<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\models\UserRole;
use herams\common\values\ProjectId;
use herams\common\values\UserId;
use herams\common\values\userRole\UserRoleId;
use herams\common\values\userRole\UserRoleTargetEnum;
use herams\common\values\WorkspaceId;
use InvalidArgumentException;

final class UserRoleRepository
{
    
    public function __construct(
        private ModelHydrator $modelHydrator,
        private ProjectRepository $projectRepository,
        private WorkspaceRepository $workspaceRepository
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function create(UserRoleRequest $userRoleRequest): UserRoleId
    {
        $record = new UserRole();
        $this->modelHydrator->hydrateActiveRecord($userRoleRequest, $record);
        if (! $record->save()) {
            throw new InvalidArgumentException(
                'Validation failed: ' . print_r($record->errors, true)
            );
        }
        return new UserRoleId($record->id);
    }

    public function retrieveUserRolesInProject(ProjectId $projectId): array
    {
        $project = $this->projectRepository->retrieveById(
            $projectId
        );
        $workspaces = $project->workspaces;
        $workspacesIds = [];
        foreach ($workspaces as $workspace) {
            $workspacesIds[] = $workspace->id;
        }
        $userRolesOfProjects = UserRole::find()
            ->where(
                [
                    'target' => UserRoleTargetEnum::project->value,
                    'target_id' => $projectId->getValue(),
                ]
            )
            ->with([
                'roleInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'lastModifiedByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'createdByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'userInfo' => fn ($query) => $query->select(
                    ['id', 'name', 'email']
                ),
            ])
            ->asArray()
            ->all();
        $userRolesOfWorkspaces = UserRole::find()
            ->where(
                [
                    'target' => UserRoleTargetEnum::workspace->value,
                    'target_id' => $workspacesIds,
                ],
            )
            ->with([
                'roleInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'lastModifiedByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'createdByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'userInfo' => fn ($query) => $query->select(
                    ['id', 'name', 'email']
                ),
                'workspaceInfo' => fn ($query) => $query->select(
                    ['id', 'i18n']
                ),
            ])
            ->asArray()
            ->all();
        foreach ($userRolesOfWorkspaces as &$userRoleOfWorkspace) {
            $userRoleOfWorkspace['projectInfo'] = $project->toArray();
        }
        foreach ($userRolesOfProjects as &$userRoleOfProject) {
            $userRoleOfProject['workspaceInfo'] = null;
            $userRoleOfProject['projectInfo'] = $project->toArray();
        }
        $userRoles = array_merge($userRolesOfProjects, $userRolesOfWorkspaces);
        usort($userRoles, fn ($a, $b) => $a['id'] <=> $b['id']);
        return $userRoles;
    }

    public function retrieveUserRolesInWorkspace(
        WorkspaceId $workspaceId
    ): array {
        $userRoles = UserRole::find()
            ->where(
                [
                    'target' => UserRoleTargetEnum::workspace->value,
                    'target_id' => $workspaceId->getValue(),
                ],
            )
            ->with([
                'roleInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'lastModifiedByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'createdByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'userInfo' => fn ($query) => $query->select(
                    ['id', 'name', 'email']
                ),
            ])
            ->asArray()
            ->all();
        $projectId = $this->workspaceRepository->getProjectId($workspaceId);
        $project = $this->projectRepository->retrieveById(
            $projectId
        )->toArray();
        $workspace = $this->workspaceRepository->retrieveById(
            $workspaceId
        )->toArray();
        foreach ($userRoles as &$userRole) {
            $userRole['projectInfo'] = $project;
            $userRole['workspaceInfo'] = $workspace;
        }
        return $userRoles;
    }

    public function retrieveUserRolesForAUser(UserId $userId)
    {
        $userRoles = UserRole::find()
            ->where(
                [
                    'user_id' => $userId->getValue(),
                ],
            )
            ->with([
                'roleInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'lastModifiedByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'createdByInfo' => fn ($query) => $query->select(
                    ['id', 'name']
                ),
                'userInfo' => fn ($query) => $query->select(
                    ['id', 'name', 'email']
                ),
            ])
            ->asArray()
            ->all();
        $cacheWorkspaceInfo = [];
        $cacheWorkspacesProjects = [];
        $cacheProjectInfo = [];
        foreach ($userRoles as &$userRole) {
            if (strtolower($userRole['target']) == strtolower(UserRoleTargetEnum::workspace->value)) {
                if (! isset($cacheWorkspaceInfo[$userRole['target_id']])) {
                    $workspace = $this->workspaceRepository->retrieveById(
                        new WorkspaceId($userRole['target_id'])
                    )->toArray();
                    $cacheWorkspaceInfo[$userRole['target_id']] = $workspace;
                }
                $userRole['workspaceInfo'] = $cacheWorkspaceInfo[$userRole['target_id']];

                if (! isset($cacheWorkspacesProjects[$userRole['target_id']])) {
                    $projectId = $this->workspaceRepository->getProjectId(
                        new WorkspaceId($userRole['target_id'])
                    );
                    $project = $this->projectRepository->retrieveById(
                        $projectId
                    )->toArray();
                    $cacheWorkspacesProjects[$userRole['target_id']] = $project;
                }
                $userRole['projectInfo'] = $cacheWorkspacesProjects[$userRole['target_id']];
            }
            if (strtolower($userRole['target']) == strtolower(UserRoleTargetEnum::project->value)) {
                if (! isset($cacheProjectInfo[$userRole['target_id']])) {
                    $project = $this->projectRepository->retrieveById(
                        new ProjectId($userRole['target_id'])
                    )->toArray();
                    $cacheProjectInfo[$userRole['target_id']] = $project;
                }
                $userRole['projectInfo'] = $cacheProjectInfo[$userRole['target_id']];
                $userRole['workspaceInfo'] = '--';
            }
            if (strtolower($userRole['target']) == strtolower(UserRoleTargetEnum::global->value)) {
                $userRole['projectInfo'] = '--';
                $userRole['workspaceInfo'] = '--';
            }
        }
        return $userRoles;
    }

    /**
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
