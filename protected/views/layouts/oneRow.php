<?php
use kartik\helpers\Html;

$this->beginContent('@app/views/layouts/main.php');
$defaultRowOptions = ['class' => 'row'];
echo Html::tag(
    'div',
    $content,
    isset($this->params['rowOptions'])
        ? \yii\helpers\ArrayHelper::merge($defaultRowOptions, $this->params['rowOptions'])
        : $defaultRowOptions
);
$this->endContent();