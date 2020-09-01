<?php
use yii\bootstrap\Html;

$this->title= \Yii::t('app', 'Admin dashboard');
$this->params['breadcrumbs'][] = ['label' => ""];

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);
echo Html::endTag('div');
