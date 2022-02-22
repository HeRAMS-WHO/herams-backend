<?php

declare(strict_types=1);

/**
 * @var \prime\components\View $this
 * @var string $content
 */


$this->beginContent('@views/layouts/css3-grid.php');
$this->registerAssetBundle(\prime\assets\AppAsset::class);

echo $this->render('//menu');

echo $content;

$this->endContent();
