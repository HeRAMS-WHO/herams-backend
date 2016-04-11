<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Tool $model
 */

//$this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);

echo Html::beginTag('div', [
    'class' => ['full-page']
]);
echo Html::tag('iframe', '', [
    'src' => $model->getIntakeUrl(),
    'class' => [],
    'style' => [
        //'height' => '800px'
    ]
]);
echo Html::endTag('div');