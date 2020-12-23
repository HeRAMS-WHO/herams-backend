<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\models\ar\Permission;

$this->title = \Yii::t('app', 'Administration');
$this->params['breadcrumbs'][] = ['label' => ""];

echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

echo Html::beginTag('div', ['class' => 'content no-tab']);
echo Html::beginTag('div', ['class' => 'action-group']);
echo Html::a(\Yii::t('app', 'Projects'), Url::to(['project/index']), ['class' => 'btn btn-default']);

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN)) {
    echo Html::a(\Yii::t('app', 'Users'), Url::to(['user/index']), ['class' => 'btn btn-default']);
    echo Html::a(\Yii::t('app', 'Backend administration'), Url::to(['admin/limesurvey']), ['class' => 'btn btn-default']);
}
if (\Yii::$app->user->can(Permission::PERMISSION_SUPER_SHARE)) {
    echo Html::a(\Yii::t('app', 'Global permissions'), Url::to(['admin/share']), ['class' => 'btn btn-default']);
}

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
