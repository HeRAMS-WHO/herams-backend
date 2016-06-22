<?php


namespace prime\components;


use dektrium\rbac\components\DbManager;
use prime\models\ar\User;
use prime\models\permissions\Permission;

class AuthManager extends DbManager
{
    public function checkAccess($userId, $permissionName, $params = [])
    {
        if ($permissionName === 'admin' && app()->user->id === $userId && app()->request->get(\prime\models\ar\User::NON_ADMIN_KEY) !== null) {
            return false;
        }
        
        $result = parent::checkAccess($userId, 'admin', $params);
            parent::checkAccess($userId, $permissionName, $params);

        // Check using custom permission table.
        if (!$result && isset($params['model'])) {
            if (isset($params['id'])) {
                return Permission::isAllowedById([[User::class, $userId]], $params['model'], $params['id'], $permissionName);
            } else {
                return Permission::anyAllowedById(User::class, $userId, $params['model'], $permissionName);
            }
        }

        return $result;
    }

}