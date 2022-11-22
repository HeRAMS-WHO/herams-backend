<?php

declare(strict_types=1);

namespace prime\widgets\menu;

use herams\common\models\Permission;

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
            'title' => \Yii::t('app', 'Dashboard'),
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users'),
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions'),
        ];


        return parent::renderMenu();
    }
}
