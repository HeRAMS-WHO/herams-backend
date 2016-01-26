<?php

use app\components\Html;
use prime\models\mapLayers\EventGrades;

/**
 * @var \yii\web\View $this
 * @var array $eventsResponses
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
                <h3 style="margin-top: 0px; margin-bottom: 0px; line-height: 34px;"><?=$lastEventResponse->getData()['CED01']?> / <?=EventGrades::mapGrade($lastEventResponse->getData()['GM02'])?> / <?=EventGrades::mapGradingStage($lastEventResponse->getData()['GM00'])?> (<?=(new \Carbon\Carbon($lastEventResponse->getData()['GM01']))->format('d/m/Y')?>)</h3>
            </div>
            <div class="col-xs-12">
                <?php
                $serie = [];
                foreach($eventResponses as $response) {
                    $serie[] = [
                        'grade' => EventGrades::mapGrade($response->getData()['GM02']),
                        'y' => EventGrades::mapValue($response->getData()['GM02']),
                        'color' => EventGrades::mapColor($response->getData()['GM02']),
                        'name' => (new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y')
                    ];
                }

                echo \miloschuman\highcharts\Highcharts::widget([
                    'options' => [
                        'chart' => [
                            'height' => 170,
                            'marginTop' => 20
                        ],
                        'title' => false,
                        'xAxis' => [
                            'type' => 'category'
                        ],
                        'yAxis' => [
                            'min' => 1,
                            'max' => 4,
                            'title' => false,
                            'labels' => [
                                'formatter' => new \yii\web\JsExpression(
                                    'function(){' .
                                    'var labelMap = ' . json_encode(EventGrades::gradeMap()) . ';' .
                                    'var valueMap = ' . json_encode(array_flip(EventGrades::valueMap())) . ';' .
                                    'return this.value > 1 ? labelMap[valueMap[this.value]] : "";' .
                                    '}'
                                )
                            ],
                            'tickInterval' => 1
                        ],
                        'series' => [
                            [
                                'data' => $serie,
                                'lineWidth' => 0,
                                'marker' => [
                                    'lineWidth' => 2,
                                    'lineColor' => '#A9A9A9',
                                    'radius' => 17
                                ],
                                'tooltip' => [
                                    'pointFormat' => '<b>{point.grade}</b><br/>'
                                ]
                            ]
                        ],
                        'legend' => [
                            'enabled' => false
                        ],
                        'credits' => [
                            'enabled' => false
                        ]
                    ],
                    'htmlOptions' => [

                    ],
                    'id' => 'event_' . $lastEventResponse->getData()['UOID']
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php
}
echo Html::endTag('div');