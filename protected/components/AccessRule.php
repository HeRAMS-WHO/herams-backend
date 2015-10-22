<?php

namespace prime\components;

use prime\models\User;

class AccessRule extends \yii\filters\AccessRule
{
    /** @inheritdoc */
    protected function matchRole($user)
    {
        if(!parent::matchRole($user)) {
            foreach ($this->roles as $role) {
                if ($role === 'admin') {
                    if (!app()->user->isGuest && app()->user->identity->isAdmin && app()->request->getQueryParam(User::NON_ADMIN_KEY) === null) {
                        return true;
                    }
                }
            }
            return false;
        }

        return true;
    }
}