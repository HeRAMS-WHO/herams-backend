<?php

namespace prime\components;

use prime\models\ar\User;

class AccessRule extends \yii\filters\AccessRule
{
    /**
     * @inheritdoc
     * @param \yii\web\User $user
     */
    protected function matchRole($user)
    {
        if(!parent::matchRole($user)) {
            foreach ($this->roles as $role) {
                if ($role === 'admin') {
                    if (!$user->isGuest && $user->identity->isAdmin && app()->request->getQueryParam(User::NON_ADMIN_KEY) === null) {
                        return true;
                    }
                }
            }
            return false;
        }

        return true;
    }
}