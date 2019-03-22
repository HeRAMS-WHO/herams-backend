<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->beginContent('@views/layouts/css3-grid.php');
$this->registerAssetBundle(\prime\assets\AppAsset::class);

echo $this->render('//menu');

echo Html::tag('div', $content, ['class' => ['content'], 'style' => [
    'display' => 'block',
//    'grid-template-columns' => 'auto',
//    'grid-template-rows' => 'auto'
//        grid-auto-rows: 200px;
//        grid-auto-flow: dense;
//        grid-row-gap: var(--gutter-size);
//        grid-column-gap: var(--gutter-size);
]]);

$this->endContent();