<?php

use app\components\Html;
use prime\models\mapLayers\HealthClusters;

/**
 * @var \yii\web\View $this
 * @var array $healthClustersResponses
 */

echo Html::beginTag('div', ['class' => 'row', 'style' => ['overflow-y' => 'auto', 'max-height' => '340px']]);
foreach($healthClustersResponses as $healthClusterResponses) {
    $lastHealthClusterResponse = $healthClusterResponses[count($healthClusterResponses) - 1];

    //TODO: select coordinator and co-coordinator from response

    $coCoordinator = \prime\models\ar\User::find()->where(['id' => $lastHealthClusterResponse->getData()["CM07"]])->one()
    ?>
    <div class="col-xs-12" style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-xs-12" style="line-height: 34px">
                <h3 style="margin-top: 0px; margin-bottom: 0px; line-height: 34px;"> <?=($lastHealthClusterResponse->getData()['LocalityID'] != '') ? $lastHealthClusterResponse->getData()['LocalityID'] . ' / ': ''?><?=HealthClusters::mapPhase($lastHealthClusterResponse->getData()['CM01'])?></h3>
            </div>
            <div class="col-xs-3">
                <?=\Yii::t('app', 'Coordinator:')?>
            </div>
            <div class="col-xs-3">
                <?php
                    if (null !== $coordinator = \prime\models\ar\User::find()->where(['id' => $lastHealthClusterResponse->getData()["CM05"]])->one()) {
                        echo implode('<br>', [
                            $coordinator->profile->first_name . ' ' . $coordinator->profile->last_name,
                            $coordinator->email,
                            $coordinator->profile->organization
                        ]);
                    } else {
                        echo \Yii::t('app', 'none');
                    }
                ?>
            </div>
            <div class="col-xs-3">
                <?=\Yii::t('app', 'Co-coordinator:')?>
            </div>
            <div class="col-xs-3">
                <?php
                if (null !== $coordinator = \prime\models\ar\User::find()->where(['id' => $lastHealthClusterResponse->getData()["CM07"]])->one()) {
                    echo implode('<br>', [
                        $coordinator->profile->first_name . ' ' . $coordinator->profile->last_name,
                        $coordinator->email,
                        $coordinator->profile->organization
                    ]);
                } else {
                    echo \Yii::t('app', 'none');
                }
                ?>
            </div>
            <div class="col-xs-12" style="margin-top: 20px;">
                <?php
                $serie = [];
                foreach($healthClusterResponses as $response) {
                    $serie[] = [
                        'phase' => HealthClusters::mapPhase($response->getData()['CM01']),
                        'y' => HealthClusters::mapValue($response->getData()['CM02']),
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
                                    'var labelMap = ' . json_encode(HealthClusters::phaseMap()) . ';' .
                                    'var valueMap = ' . json_encode(array_flip(HealthClusters::valueMap())) . ';' .
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
                    'id' => 'event_' . $lastHealthClusterResponse->getData()['UOID']
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php
}
echo Html::endTag('div');