<?php
use kartik\helpers\Html;

$this->beginContent('@app/views/layouts/main.php');
echo Html::tag('div', $content, ['class' => 'row']);
$this->endContent();