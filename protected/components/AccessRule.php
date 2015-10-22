<?php

namespace prime\components;

class AccessRule extends \yii\filters\AccessRule
{
    /** @inheritdoc */
    protected function matchRole($user)
    {
        if(!parent::matchRole($user)) {
            foreach ($this->roles as $role) {
                if ($role === 'admin') {
                    if (!app()->user->isGuest && app()->user->identity->isAdmin) {
                        return true;
                    }
                }
            }
            return false;
        }

        return true;
    }
}