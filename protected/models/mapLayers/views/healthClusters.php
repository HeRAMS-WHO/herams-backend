<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $healthClusterResponses
 * @var \prime\models\mapLayers\HealthClusters $mapLayer
 */

$lastHealthClusterResponse = $healthClusterResponses[count($healthClusterResponses) - 1];
$mapLayer = $this->context;
//TODO: select coordinator and co-coordinator from response
$coordinator = \prime\models\ar\User::find()->all()[0];
$coCoordinator = \prime\models\ar\User::find()->all()[1];

?>
<div class="row">
    <div class="col-xs-12">
        <h2 style="margin-top: 0px;"><?=$country->name?> / <?=($lastHealthClusterResponse->getData()['LocalityID'] != '') ? $lastHealthClusterResponse->getData()['LocalityID'] . ' / ': ''?><?=$mapLayer->mapPhase($lastHealthClusterResponse->getData()['CM01'])?></h2>
    </div>
    <div class="col-xs-3">
        <?=\Yii::t('app', 'Coordinator:')?>
    </div>
    <div class="col-xs-3">
        <?=$coordinator->profile->first_name . ' ' . $coordinator->profile->last_name?><br>
        <?=$coordinator->email?><br>
        <?=$coordinator->profile->organization?>
    </div>
    <div class="col-xs-3">
        <?=\Yii::t('app', 'Co-coordinator:')?>
    </div>
    <div class="col-xs-3">
        <?=$coCoordinator->profile->first_name . ' ' . $coCoordinator->profile->last_name?><br>
        <?=$coCoordinator->email?><br>
        <?=$coCoordinator->profile->organization?>
    </div>
    <div class="col-xs-12"><h3><?=\Yii::t('app' , 'Timeline')?></h3></div>
    <div class="col-xs-12">
        <?php
        $serie = [];
        foreach($healthClusterResponses as $response) {
            $serie[] = [
                'phase' => $mapLayer->mapPhase($response->getData()['CM01']),
                'y' => $mapLayer->mapValue($response->getData()['CM02']),
                'name' => (new \Carbon\Carbon($response->getData()['CM03']))->format('d/m/Y')
            ];
        }

        echo \miloschuman\highcharts\Highcharts::widget([
            'options' => [
                'chart' => [
                    'height' => 100,
                    'marginTop' => 20
                ],
                'title' => false,
                'xAxis' => [
                    'type' => 'category'
                ],
                'yAxis' => [
                    'min' => 0,
                    'max' => 1,
                    'title' => false,
                    'labels' => [
                        'formatter' => new \yii\web\JsExpression(
                            'function(){' .
                            'var labelMap = ' . json_encode($mapLayer->phaseMap()) . ';' .
                            'var valueMap = ' . json_encode(array_flip($mapLayer->valueMap())) . ';' .
                            'return this.value > 0 ? labelMap[valueMap[this.value]] : "";' .
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
                            'pointFormat' => '<b>{point.phase}</b><br/>'
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