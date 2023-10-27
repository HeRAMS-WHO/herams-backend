<?php

declare(strict_types=1);

namespace herams\common\domain\role;

use herams\common\models\Role;
use herams\common\values\role\RoleId;
use herams\common\values\role\RoleTypeEnum;

final class RoleRepository
{
    /**
     * @param  array  $roleIds
     * @param  RoleTypeEnum  $scope
     *
     * @return bool
     */
    public function checkIfEveryRoleHasScope(
        array $roleIds,
        RoleTypeEnum $scope
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
