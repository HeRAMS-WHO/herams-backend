<?php
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'Administration');
$this->params['breadcrumbs'][] = ['label' => ""];

echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

echo Html::beginTag('div', ['class' => 'content no-tab']);

echo Html::a(\Yii::t('app', 'Projects'), Url::to(['project/index']), ['class' => 'btn btn-default']);

echo Html::endTag('div');
echo Html::endTag('div');
