<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var array $healthClustersResponses
 * @var array $countriesResponses
 * @var array $eventsResponses
 */

//filter responses
$countries = [];
foreach($healthClustersResponses as $uoid => $healthClusterResponses) {
    $lastHealthClusterResponse = $healthClusterResponses[count($healthClusterResponses) - 1];
    if($lastHealthClusterResponse->getData()['CM00'] == 'A2' || $lastHealthClusterResponse->getData()['CM00'] == '') {
        unset($healthClustersResponses[$uoid]);
        continue;
    }
    $countries[$lastHealthClusterResponse->getData()['PRIMEID']] = true;
}

//prepare country grading series
$countriesData = [];
foreach($countriesResponses as $iso3 => $countryResponses) {
    $lastCountryResponse = $countryResponses[count($countryResponses) - 1];
    if($lastCountryResponse->getData()['GM02'] == '' || !isset($countries[$iso3])) {
        unset($countriesResponses[$iso3]);
        continue;
    }

    if(!isset($countriesData[$lastCountryResponse->getData()['GM02']])) {
        $countriesData[$lastCountryResponse->getData()['GM02']] = 0;
    }

    $countriesData[$lastCountryResponse->getData()['GM02']]++;
}

ksort($countriesData);
$countryGradingSerie = [];
foreach($countriesData as $value => $count) {
    $countryGradingSerie[] = [
        'name' => \prime\models\mapLayers\CountryGrades::mapGrade($value),
        'color' => (string) \prime\models\mapLayers\CountryGrades::mapColor($value),
        'count' => $count,
        'y' => round($count * 100 / array_sum($countriesData))
    ];
}

//prepare events series
$eventsData = [];
foreach($eventsResponses as $uoid => $eventResponses) {
    $lastEventResponse = $eventResponses[count($eventResponses) - 1];
    if($lastEventResponse->getData()['GM02'] == 'A0' || $lastEventResponse->getData()['GM02'] == '' ||!isset($countries[$lastEventResponse->getData()['PRIMEID']])) {
        unset($eventsResponses[$uoid]);
        continue;
    }

    if(!isset($eventsData[$lastEventResponse->getData()['CED02']])) {
        $eventsData[$lastEventResponse->getData()['CED02']] = 0;
    }

    $eventsData[$lastEventResponse->getData()['CED02']]++;
}

arsort($eventsData);

$eventsSerie = [];
foreach($eventsData as $value => $count) {
    $eventsSerie[] = [
        'name' => \prime\models\mapLayers\EventGrades::mapType($value),
        'count' => $count,
        'y' => round($count * 100 / array_sum($eventsData))
    ];
}

?>
<div class="row">
    <?=$this->render('greyHeader')?>
    <div class="col-xs-12 h3"><span class="h1"><?=count($healthClustersResponses)?></span> <?=\Yii::t('app', 'Active health clusters')?></div>
    <div class="col-sm-6"><h4 class="chart-head"><?=\Yii::t('app', 'in {amount} graded countries', ['amount' => count($countriesResponses)])?></h4></div>
    <div class="col-sm-6"><h4 class="chart-head"><?=\Yii::t('app', 'having {amount} events', ['amount' => count($eventsResponses)])?></h4></div>
    <?php
    echo \miloschuman\highcharts\Highcharts::widget([
        'options' => [
            'chart' => [
                'type' => 'pie',
                'height' => 350,
                'marginBottom' => 80,
                'spacingTop' => 0
            ],
            'title' => false,
            'series' => [
                [
                    'data' => $countryGradingSerie,
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'tooltip' => [
                        'pointFormat' => '{point.count} ' . \Yii::t('app', 'Countries') . '<br><b>{point.y}%</b><br/>'
                    ],
                    'showInLegend' => true,
                    'innerSize' => '50%',
                ]
            ],
            'credits' => [
                'enabled' => false
            ]
        ],
        'htmlOptions' => [
            'class' => 'col-sm-6'
        ],
        'id' => 'health-clusters-grading'
    ]);
    ?>
    <?php
    echo \miloschuman\highcharts\Highcharts::widget([
        'options' => [
            'chart' => [
                'type' => 'pie',
                'height' => 350,
                'marginBottom' => 80,
                'spacingTop' => 0
            ],
            'title' => false,
            'series' => [
                [
                    'data' => $eventsSerie,
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'tooltip' => [
                        'pointFormat' => '{point.count} ' . \Yii::t('app', 'Events') . '<br><b>{point.y}%</b><br/>'
                    ],
                    'showInLegend' => true,
                    'innerSize' => '50%',
                ]
            ],
            'credits' => [
                'enabled' => false
            ],
        ],
        'htmlOptions' => [
            'class' => 'col-sm-6'
        ],
        'id' => 'health-clusters-events'
    ]);
    ?>
    <?=$this->render('greyFooter')?>
</div>
