<?php

declare(strict_types=1);

use herams\common\domain\element\Element;
use prime\assets\DashboardBundle;
use prime\components\View;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/**
 * @var Element $element
 * @var View $this
 * @var SurveyInterface $survey
 * @var iterable $data
 */

$this->registerAssetBundle(DashboardBundle::class);
$this->registerAssetBundle(JqueryAsset::class);

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

echo Html::beginTag('div', [
    'class' => 'content',
]);
echo $element->getWidget($survey, $data, $element->page)->run();
echo Html::endTag('div');
