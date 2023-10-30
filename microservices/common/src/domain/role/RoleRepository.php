<?php

declare(strict_types=1);

namespace herams\common\domain\role;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\models\Role;
use herams\common\values\role\RoleId;
use herams\common\values\role\RoleScopEnum;
use herams\common\values\role\RoleTypeEnum;
use herams\common\values\WorkspaceId;

final class RoleRepository
{
    /**
     * @param  array  $roleIds
     * @param  RoleScopEnum  $scope
     *
     * @return bool
     */
    public function checkIfEveryRoleHasScope(
        array $roleIds,
        RoleScopEnum $scope
    ): bool {
        $roles = $this->retrieveRoles($roleIds);
        foreach ($roles as $role) {
            if (strtolower($role->scope) !== $scope->getValue()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param  array  $roleIds
     *
     * @return Role[]
     */
    public function retrieveRoles(array $roleIds): array
    {
        $roles = Role::find()->where(['id' => $roleIds])->all();
        $roleObjects = [];
        foreach ($roles as $role) {
            $roleObjects[] = new Role($role);
        }
        return $roleObjects;
    }

    public function retrieveRolesInWorkspaces(
        WorkspaceId $workspaceId,
        WorkspaceRepository $workspaceRepository
    ): array {
        $projectId = $workspaceRepository->retrieveById(
            $workspaceId
        )->project_id;
        $roles = Role::find()->where([
                'or',
                [
                    'scope' => RoleScopEnum::workspace->getValue(),
                    'type'  => RoleTypeEnum::standard->getValue(),
                ],
                [
                    'scope'      => RoleScopEnum::workspace->getValue(),
                    'type'       => RoleTypeEnum::custom->getValue(),
                    'project_id' => $projectId
                ],
            ]
        )->all();
        $roleObjects = [];
        foreach ($roles as $role) {
            $roleObjects[] = new Role($role);
        }
        return $roleObjects;
    }

    /**
     * @param  RoleId  $roleId
     *
     * @return Role
     */
    public function retrieveRole(RoleId $roleId): Role
    {
        return Role::findOne(['id' => $roleId->getValue()]);
    }
}
