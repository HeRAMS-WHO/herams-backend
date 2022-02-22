<?php

declare(strict_types=1);

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;

$this->beginContent('@views/layouts/css3-grid.php');
$this->registerAssetBundle(\prime\assets\AppAsset::class);


echo $this->render('//menu');

echo Html::tag('div', $content, ['class' => [
    'content',
    "layout-{$this->context->layout}",
    "controller-{$this->context->id}",
    "action-{$this->context->action->id}"

], 'style' => [
]]);

$this->endContent();
