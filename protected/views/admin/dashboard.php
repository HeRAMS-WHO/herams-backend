<?php
declare(strict_types=1);

use prime\components\View;
use prime\models\ar\Permission;

/**
 * @var View $this
 */
$this->title = \Yii::t('app', 'Dashboard');

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

\prime\widgets\Section::begin([
    'actions' => [
        [
            'link' => ['project/index'],
            'label' => \Yii::t('app', 'Projects'),
            'style' => 'default'
        ]
    ]
]);

\prime\widgets\Section::end();
