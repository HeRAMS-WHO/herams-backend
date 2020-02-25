<?php

use prime\models\ar\Project;
use prime\widgets\menu\SideMenu;
use yii\helpers\Html;

SideMenu::begin([
    'footer' => $this->render('//footer', ['projects' => Project::find()->all()])
]);
/** @var \prime\components\Controller $controller */
$controller = $this->context;
echo Html::a('Projects', ['/project/index'],
    ['class' => $controller->action->uniqueId === 'project/index' ? 'active' : null]);
if (\Yii::$app->user->can(\prime\models\permissions\Permission::PERMISSION_ADMIN)) {
    echo Html::a('Users', ['/user/index'],
        ['class' => $controller->action->uniqueId === 'user/index' ? 'active' : null]);
    echo Html::a('Global admins', ['/admin/share'],
        ['class' => $controller->action->uniqueId === 'admin/share' ? 'active' : null]);
}
echo Html::a(\Yii::t('app', 'Backend administration'), ['/admin/limesurvey'],
    ['class' => $controller->action->uniqueId === 'admin/limesurvey' ? 'active' : null]);
SideMenu::end();