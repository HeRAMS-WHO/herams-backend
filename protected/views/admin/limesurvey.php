<?php
declare(strict_types=1);

use prime\helpers\Icon;
use yii\helpers\Html;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;

/**
 * @var \prime\components\View $this
 *
 */
$this->title = \Yii::t('app', 'Backend administration');
$this->params['breadcrumbs'][] = ['label' => ""];


$this->params['tabs'] = [
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['admin/dashboard'],
        'title' => \Yii::t('app', 'Dashboard')
    ],
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['user/index'],
        'title' => \Yii::t('app', 'Users')
    ],
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['admin/share'],
        'title' => \Yii::t('app', 'Global permissions')
    ],
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['admin/limesurvey'],
        'title' => \Yii::t('app', 'Backend administration')
    ]
];

echo $this->render('@views/shared/limesurvey-iframe');
