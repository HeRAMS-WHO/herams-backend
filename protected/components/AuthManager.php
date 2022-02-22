<?php

namespace prime\components;

class AuthManager extends \SamIT\Yii2\abac\AuthManager
{
    public function checkAccess($userId, $permissionName, $params = [])
    {
        if (is_object($params)) {
            $params = [self::TARGET_PARAM => $params];
        }

        $result = parent::checkAccess($userId, $permissionName, $params);
        return $result;
    }
}
