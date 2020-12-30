<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;

$this->title = \Yii::t('app', 'Administration');
$this->params['breadcrumbs'][] = ['label' => ""];

echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

$tabs = [
    [
        'url' => ['project/index'],
        'title' => \Yii::t('app', 'Projects')
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN)) {
    $tabs[] =
        [
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users')
        ];
    $tabs[] =
        [
            'url' => ['admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];
}
if (\Yii::$app->user->can(Permission::PERMISSION_SUPER_SHARE)) {
    $tabs[] =
        [
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => 'content']);
echo Html::beginTag('div', ['class' => 'action-group']);
echo Html::a(\Yii::t('app', 'Projects'), Url::to(['project/index']), ['class' => 'btn btn-default']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
