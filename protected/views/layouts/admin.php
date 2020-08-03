<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->beginContent('@views/layouts/css3-grid.php');
$this->registerAssetBundle(\prime\assets\AppAsset::class);

echo $this->render('//menu');

echo $content;

$this->endContent();
