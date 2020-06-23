<?php

/** @var Element $element */
/** @var \yii\web\View $this */
/** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */

use prime\models\ar\Element;

$this->registerAssetBundle(\prime\assets\DashboardBundle::class);
$this->registerAssetBundle(\yii\web\JqueryAsset::class);
$this->registerCss(<<<CSS
    body {
        display: block;
    }

.content {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: 1fr;
    width: 100vw;
    padding: 0;
    height: 100vh;
    
}
CSS
);
echo \yii\helpers\Html::beginTag('div', ['class'=> 'content']);
echo $element->getWidget($survey, $data, $element->page)->run();
echo \yii\helpers\Html::endTag('div');
