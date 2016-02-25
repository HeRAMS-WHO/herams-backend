<?php

use app\components\Html;
use prime\models\mapLayers\EventGrades;

/**
 * @var \yii\web\View $this
 * @var array $eventsResponses
 * @var \prime\models\Country $country
 * @var boolean $popup
 */

echo Html::beginTag('div', ['class' => 'row', 'style' => ['overflow-y' => 'auto', 'max-height' => '340px']]);
foreach($eventsResponses as $eventResponses) {
    $lastEventResponse = $eventResponses[count($eventResponses) - 1];
    ?>
    <div class="col-xs-12" style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-xs-1">
                <div style="height: 34px; width: 34px; border: 2px solid darkgrey; border-radius: 50%; background-color: <?=EventGrades::mapColor($lastEventResponse->getData()['GM02'])?>;"></div>
            </div>
            <div class="col-xs-11" style="line-height: 34px">
                <h3 style="margin-top: 0px; margin-bottom: 0px; line-height: 34px;"><?=Html::a($lastEventResponse->getData()['CED01'], ['/marketplace/event-dashboard', 'iso_3' => $country->iso_3, 'id' => $lastEventResponse->getData()['UOID'], 'popup' => $popup, 'layer' => 'eventGrades'])?> / <?=EventGrades::mapGradingStage($lastEventResponse->getData()['GM00'])?> (<?=(new \Carbon\Carbon($lastEventResponse->getData()['GM01']))->format('d/m/Y')?>)</h3>
            </div>
            <div class="col-xs-12">
                <?=$this->render('eventGraph', ['eventResponses' => $eventResponses])?>
            </div>
        </div>
    </div>
    <?php
}
echo Html::endTag('div');