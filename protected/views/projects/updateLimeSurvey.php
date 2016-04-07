<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

//$this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);

echo Html::beginTag('div', [
    'class' => ['full-page']
]);
echo Html::tag('iframe', '', [
    'src' => $model->getSurveyUrl(),
    'class' => [],
    'style' => [
        //'height' => '800px'
    ]
]);
echo Html::endTag('div');