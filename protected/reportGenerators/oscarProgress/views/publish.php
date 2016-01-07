<?php

use app\components\Html;

/**
 * @var \yii\web\View $this;
 */

$this->registerAssetBundle(\prime\assets\SassAsset::class);
$this->beginContent('@app/views/layouts/report.php');

$this->endContent();
?>