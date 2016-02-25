<?php

use prime\models\mapLayers\EventGrades;

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 * @var string $id
 * @var array $eventsResponses
 * @var string $layer
 * @var \prime\models\forms\MarketplaceFilter $filter
 */

$lastGradingResponse = !empty($eventsResponses[$id]) ? $eventsResponses[$id][count($eventsResponses[$id]) - 1] : null;
?>

<style>
    .timeline-block {
        text-align: center;
        border: 2px solid darkgrey;
        border-radius: 5px;
        height: 60px;
        padding-top: 16px;
        width: 100px;
        font-size: 1.3em;
        font-weight: bold;
    }
</style>

<div class="col-xs-10">
    <h1 style="margin-top: 0px; margin-bottom:0px;"><?=$lastGradingResponse->getData()['CED01']?></h1>
    <h2 style="margin-top: 5px;"><?=\app\components\Html::a($country->name, ['/marketplace/country-dashboard', 'iso_3' => $country->iso_3, 'layer' => 'eventGrades', 'popup' => $popup])?></h2>
    <h3 style="margin-top: 5px; margin-bottom: 0px;"><?=EventGrades::mapType($lastGradingResponse->getData()['CED02'])?></h3>
    <?=$lastGradingResponse->getData()['GLIDE'] != '' ? \Yii::t('app', 'Glide number') . ': ' . $lastGradingResponse->getData()['GLIDE'] : ''?>
</div>
<div class="col-xs-2">
    <?php if(isset($lastGradingResponse)) { ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="timeline-block pull-right" style="background-color: <?=EventGrades::mapColor($lastGradingResponse->getData()['GM02'])?>;">
                <?=EventGrades::mapGrade($lastGradingResponse->getData()['GM02'])?>
            </div>
        </div>
        <div class="col-xs-12" style="text-align: right; margin-bottom: 10px;">
            <?=EventGrades::mapGradingStage($lastGradingResponse->getData()['GM00'])?><br>
            <?=(new \Carbon\Carbon($lastGradingResponse->getData()['GM01']))->format('d/m/Y')?>
        </div>
    </div>
    <?php } ?>
</div>
<div class="col-xs-12">
    <?=$this->render('/marketplace/filter', ['filter' => $filter])?>
</div>
<div class="col-xs-12">
    <?=\yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('app', 'Grading'),
                'content' => $this->render('country/eventGraph', ['eventResponses' => $eventsResponses[$id]]),
                'visible' => !empty($eventsResponses),
            ],
        ],
        'options' => [
            'style' => [
                'margin-bottom' => '10px'
            ]
        ]
    ])?>
</div>