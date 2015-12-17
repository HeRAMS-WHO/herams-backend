<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $eventResponses
 * @var \prime\models\mapLayers\CountryGrades $mapLayer
 */

$lastEventResponse = $eventResponses[count($eventResponses) - 1];
$mapLayer = $this->context;
?>

<style>
    .timeline-block {
        text-align: center;
        border: 2px solid darkgrey;
        border-radius: 5px;
        height: 60px;
        padding-top: 18px;
        width: 100px;
    }
</style>

<div class="row">
    <div class="col-xs-8">
        <h2 style="margin-top: 0px;"><?=$country->name?> / <?=$lastEventResponse->getData()['CED01']?></h2>
    </div>
    <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-12">
                <div class="timeline-block pull-right" style="background-color: <?=$mapLayer->mapColor($lastEventResponse->getData()['GM02'])?>;">
                    <?=$mapLayer->mapGrade($lastEventResponse->getData()['GM02'])?>
                </div>
            </div>
            <div class="col-xs-12" style="text-align: right">
                <?=$mapLayer->mapGradingStage($lastEventResponse->getData()['GM00'])?><br>
                <?=(new \Carbon\Carbon($lastEventResponse->getData()['GM01']))->format('d F Y')?>
            </div>
        </div>
    </div>
    <div class="col-xs-12"><h3><?=\Yii::t('app' , 'Grade monitoring')?></h3></div>
    <div class="col-xs-12">
        <?php
        $categories = [];
        $serie = [];
        foreach($eventResponses as $response) {
            $categories[] = (new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y');
            $serie[] = [
                'grade' => $mapLayer->mapGrade($response->getData()['GM02']),
                'y' => $mapLayer->mapValue($response->getData()['GM02']),
                'color' => $mapLayer->mapColor($response->getData()['GM02']),
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
                    'min' => 2,
                    'max' => 4,
                    'title' => false,
                    'labels' => [
                        'formatter' => new \yii\web\JsExpression(
                            'function(){' .
                            'var labelMap = ' . json_encode($mapLayer->gradeMap()) . ';' .
                            'var valueMap = ' . json_encode(array_flip($mapLayer->valueMap())) . ';' .
                            'return labelMap[valueMap[this.value]];' .
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
            'id' => 'timeline'
        ]);
        ?>
    </div>
</div>