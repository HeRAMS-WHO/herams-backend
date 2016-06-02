<?php


namespace prime\components;


use dektrium\rbac\components\DbManager;

class AuthManager extends DbManager
{
    public function checkAccess($userId, $permissionName, $params = [])
    {
        if ($permissionName === 'admin' && app()->user->id === $userId && app()->request->get(\prime\models\ar\User::NON_ADMIN_KEY) !== null) {
            return false;
        }
        return parent::checkAccess($userId, $permissionName, $params);
    }

}