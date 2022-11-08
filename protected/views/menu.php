<?php

declare(strict_types=1);

use prime\widgets\menu\SideMenu;
use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 */
SideMenu::begin([
    'footer' => $this->render('//footer'),
]);
$controller = $this->context;
echo Html::a(
    \Yii::t('app', 'Admin dashboard'),
    ['/admin'],
    [
        'class' => $controller->action->uniqueId === 'admin/dashboard' ? 'active' : null,
    ]
);
echo Html::a(
    \Yii::t('app', 'Projects'),
    ['/project/index'],
    [
        'class' => $controller->action->uniqueId === 'project/index' ? 'active' : null,
    ]
);
if (\Yii::$app->user->can(\herams\common\models\Permission::PERMISSION_ADMIN)) {
    echo Html::a(
        \Yii::t('app', 'Users'),
        ['/user/index'],
        [
            'class' => $controller->action->uniqueId === 'user/index' ? 'active' : null,
        ]
    );
    echo Html::a(
        \Yii::t('app', 'Global permissions'),
        ['/admin/share'],
        [
            'class' => $controller->action->uniqueId === 'admin/share' ? 'active' : null,
        ]
    );
}
echo Html::a(
    \Yii::t('app', 'Backend administration'),
    ['/admin/limesurvey'],
    [
        'class' => $controller->action->uniqueId === 'admin/limesurvey' ? 'active' : null,
    ]
);
SideMenu::end();
