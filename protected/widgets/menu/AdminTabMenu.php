<?php
declare(strict_types=1);

namespace prime\widgets\menu;

use prime\models\ar\Permission;
use prime\models\ar\Project;

/**
 * Class Menu
 * Implements a tab menu for admin pages
 * @package prime\widgets\menu
 */
class AdminTabMenu extends TabMenu
{
    protected function renderMenu(): string
    {
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['admin/dashboard'],
            'title' => \Yii::t('app', 'Dashboard')
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users')
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions')
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];

        return parent::renderMenu();
    }
}
