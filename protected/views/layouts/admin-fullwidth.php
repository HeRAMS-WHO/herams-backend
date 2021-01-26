<?php
declare(strict_types=1);

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $content
 */

$this->beginContent('@views/layouts/css3-grid.php');
$this->registerAssetBundle(\prime\assets\AppAsset::class);


echo $this->render('//menu');

echo Html::tag('div', $content, ['class' => [
    'content',
    "layout-{$this->context->layout}",
    "controller-{$this->context->id}",
    "action-{$this->context->action->id}"

], 'style' => [
    'display' => 'block'
]]);

$this->endContent();
