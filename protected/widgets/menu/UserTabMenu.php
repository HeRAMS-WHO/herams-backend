<?php

declare(strict_types=1);

namespace prime\widgets\menu;

use prime\models\ar\User;

class UserTabMenu extends TabMenu
{
    public User $user;

    public function init(): void
    {
        parent::init();
        $this->permissionSubject = $this->user;
    }

    protected function renderMenu(): string
    {
        $this->tabs[] = [
            'url' => ['user/profile'],
            'title' => \Yii::t('app', 'Profile')
        ];
        $this->tabs[] = [
            'url' => ['user/password'],
            'title' => \Yii::t('app', 'Password')
        ];
        $this->tabs[] = [
            'url' => ['user/email'],
            'title' => \Yii::t('app', 'Email')
        ];
        $this->tabs[] = [
            'url' => ['user/notifications'],
            'title' => \Yii::t('app', 'Notifications')
        ];
        $this->tabs[] = [
            'url' => ['user/access-requests'],
            'title' => \Yii::t('app', 'Access requests')
        ];

        return parent::renderMenu();
    }
}
