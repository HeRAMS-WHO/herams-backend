<?php

/** @var Element $element */
/** @var \yii\web\View $this */
/** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */

use prime\models\ar\Element;

$this->registerAssetBundle(\prime\assets\DashboardBundle::class);
$this->registerAssetBundle(\yii\web\JqueryAsset::class);
$this->registerCss(<<<CSS
    body {
        background: none;
    }

    .content {
        padding: 0;
        display: block;
        height: min-content;
    }
CSS);
echo \yii\helpers\Html::beginTag('div', ['class' => 'content']);
echo $element->getWidget($survey, $data, $element->page)->run();
echo \yii\helpers\Html::endTag('div');
