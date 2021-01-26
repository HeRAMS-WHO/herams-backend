<?php
declare(strict_types=1);

use prime\models\ar\Element;

/**
 * @var Element $element
 * @var \prime\components\View $this
 * @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey
 * @var iterable $data
 */


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
    .map {
        min-height: 400px;
    }
CSS);
echo \yii\helpers\Html::beginTag('div', ['class' => 'content']);
echo $element->getWidget($survey, $data, $element->page)->run();
echo \yii\helpers\Html::endTag('div');
