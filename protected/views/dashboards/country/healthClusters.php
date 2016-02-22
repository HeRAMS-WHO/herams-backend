<?php

use app\components\Html;
use prime\models\mapLayers\HealthClusters;

/**
 * @var \yii\web\View $this
 * @var string $id
 * @var array $healthClustersResponses
 * @var \prime\models\forms\MarketplaceFilter $filter
 */

echo Html::beginTag('div', ['class' => 'row', 'style' => ['overflow-y' => 'auto', 'max-height' => '340px']]);

//if not isset($id), than it is a country dashboard, pick the response group that is national

foreach($healthClustersResponses as $uoid => $responses) {
    if($responses[0]->getData()['CM00'] == 'A1') {
        $nationalClusterId = $uoid;
        break;
    }
}

if(!isset($id) && isset($nationalClusterId)) {
    $id = $nationalClusterId;
}

$subnational = isset($id) ? ($healthClustersResponses[$id][0]->getData()['CM00'] == 'A2') : false;

//it could be that there is no "current" cluster to show (example, country dashboard with no national cluster but subnational clusters)
if(isset($id)) {
    $currentHealthClusterResponses = $healthClustersResponses[$id];
    $lastHealthClusterResponse = $currentHealthClusterResponses[count($currentHealthClusterResponses) - 1];

    ?>
    <div class="col-xs-12" style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-xs-12" style="line-height: 34px">
                <h3 style="margin-top: 0px; margin-bottom: 0px; line-height: 34px;"> <?= ($lastHealthClusterResponse->getData(
                        )['LocalityID'] != '') ? $lastHealthClusterResponse->getData(
                        )['LocalityID'] . ' / ' : '' ?><?= HealthClusters::mapPhase(
                        $lastHealthClusterResponse->getData()['CM01']
                    ) ?></h3>
            </div>
            <div class="col-xs-3">
                <?= \Yii::t('app', 'Coordinator:') ?>
            </div>
            <div class="col-xs-3">
                <?php
                // Todo: Possibly refactor to not do queries in view.
                if (null !== $coordinator = \prime\models\ar\User::find()->where(
                        ['id' => $lastHealthClusterResponse->getData()["CM05"]]
                    )->one()
                ) {
                    echo implode(
                        '<br>',
                        [
                            $coordinator->profile->first_name . ' ' . $coordinator->profile->last_name,
                            $coordinator->email,
                            $coordinator->profile->organization
                        ]
                    );
                } else {
                    echo \Yii::t('app', 'none');
                }
                ?>
            </div>
            <div class="col-xs-3">
                <?= \Yii::t('app', 'Co-coordinator:') ?>
            </div>
            <div class="col-xs-3">
                <?php
                // Todo: Possibly refactor to not do queries in view.
                if (null !== $coordinator = \prime\models\ar\User::find()->where(
                        ['id' => $lastHealthClusterResponse->getData()["CM07"]]
                    )->one()
                ) {
                    echo implode(
                        '<br>',
                        [
                            $coordinator->profile->first_name . ' ' . $coordinator->profile->last_name,
                            $coordinator->email,
                            $coordinator->profile->organization
                        ]
                    );
                } else {
                    echo \Yii::t('app', 'none');
                }
                ?>
            </div>
<!--            <div class="col-xs-12" style="margin-top: 20px;">-->
<!--                --><?php
//                $serie = [];
//                foreach ($currentHealthClusterResponses as $response) {
//                    $serie[] = [
//                        'phase' => HealthClusters::mapPhase($response->getData()['CM01']),
//                        'y' => HealthClusters::mapValue($response->getData()['CM02']),
//                        'name' => (new \Carbon\Carbon($response->getData()['CM03']))->format('d/m/Y')
//                    ];
//                }
//
//                echo \miloschuman\highcharts\Highcharts::widget(
//                    [
//                        'options' => [
//                            'chart' => [
//                                'height' => 100,
//                                'marginTop' => 20
//                            ],
//                            'title' => false,
//                            'xAxis' => [
//                                'type' => 'category'
//                            ],
//                            'yAxis' => [
//                                'min' => 0,
//                                'max' => 1,
//                                'title' => false,
//                                'labels' => [
//                                    'formatter' => new \yii\web\JsExpression(
//                                        'function(){' .
//                                        'var labelMap = ' . json_encode(HealthClusters::phaseMap()) . ';' .
//                                        'var valueMap = ' . json_encode(array_flip(HealthClusters::valueMap())) . ';' .
//                                        'return this.value > 0 ? labelMap[valueMap[this.value]] : "";' .
//                                        '}'
//                                    )
//                                ],
//                                'tickInterval' => 1
//                            ],
//                            'series' => [
//                                [
//                                    'data' => $serie,
//                                    'lineWidth' => 0,
//                                    'marker' => [
//                                        'lineWidth' => 2,
//                                        'lineColor' => '#A9A9A9',
//                                        'radius' => 17
//                                    ],
//                                    'tooltip' => [
//                                        'pointFormat' => '<b>{point.phase}</b><br/>'
//                                    ]
//                                ]
//                            ],
//                            'legend' => [
//                                'enabled' => false
//                            ],
//                            'credits' => [
//                                'enabled' => false
//                            ]
//                        ],
//                        'htmlOptions' => [
//
//                        ],
//                        'id' => 'event_' . $lastHealthClusterResponse->getData()['UOID']
//                    ]
//                );
//                ?>
<!--            </div>-->
        </div>
    </div>
    <?php
}

echo Html::beginTag('div', ['class' => ['col-xs-12']]);
if($subnational) {
    echo Html::tag('h3', \Yii::t('app', 'National coordination structure'));
    if(isset($nationalClusterId)) {
        $lastNationalResponse = $healthClustersResponses[$nationalClusterId][count($healthClustersResponses[$nationalClusterId]) - 1];
        $country = \prime\models\Country::findOne($lastNationalResponse->getData()['PRIMEID']);
        echo Html::a(
            $country->name . ' / ' . HealthClusters::mapType($lastNationalResponse->getData()['CM02']),
            [
                '/marketplace/country-dashboard',
                'MarketplaceFilter' => $filter->getAttributes(),
                'iso_3' => $lastNationalResponse->getData()['PRIMEID'],
                'layer' => 'healthClusters',
                'popup' => $popup
            ]
        );
    }
} else {
    echo Html::tag('h3', \Yii::t('app', 'Subnational coordination structures'));
    foreach($healthClustersResponses as $uoid => $responses) {
        if(!isset($id) || $uoid != $id) {
            $lastSubationalResponse = $responses[count($responses) - 1];
            echo Html::a(
                $lastSubationalResponse->getData()['LocalityID'] . ' / ' . HealthClusters::mapType($lastSubationalResponse->getData()['CM02']),
                [
                    '/marketplace/health-cluster-dashboard',
                    'MarketplaceFilter' => $filter->getAttributes(),
                    'iso_3' => $lastSubationalResponse->getData()['PRIMEID'],
                    'layer' => 'healthClusters',
                    'id' => $uoid,
                    'popup' => $popup
                ]
            ) . '<br>';
        }
    }
}
echo Html::endTag('div');

echo Html::endTag('div');