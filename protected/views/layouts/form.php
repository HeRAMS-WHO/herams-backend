<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->beginContent('@views/layouts/admin.php');


echo Html::tag('div', $content, ['class' => ['form-layout']]);

$this->endContent();