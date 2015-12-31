<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var array $eventsResponses
 */

//filter responses
$eventCount = [];
$typeCount = [];
foreach($eventsResponses as $uoid => $eventResponses) {
    $lastEventResponse = $eventResponses[count($eventResponses) - 1];
    if($lastEventResponse->getData()['GM02'] == 'A0' || $lastEventResponse->getData()['GM02'] == '') {
        unset($eventsResponses[$uoid]);
        continue;
    }

    if(!isset($eventCount[$lastEventResponse->getData()['GM02']])) {
        $eventCount[$lastEventResponse->getData()['GM02']] = 0;
    }

    if(!isset($typeCount[$lastEventResponse->getData()['CED02']])) {
        $typeCount[$lastEventResponse->getData()['CED02']] = 0;
    }

    $eventCount[$lastEventResponse->getData()['GM02']]++;
    $typeCount[$lastEventResponse->getData()['CED02']]++;
}

ksort($eventCount);
arsort($typeCount);
$countSerie = [];
foreach($eventCount as $value => $count) {
    $countSerie[] = [
        'name' => \prime\models\mapLayers\EventGrades::mapGrade($value),
        'color' => \prime\models\mapLayers\EventGrades::mapColor($value),
        'count' => $count,
        'y' => round($count * 100 / array_sum($eventCount))
    ];
}

$typeSerie = [];
foreach($typeCount as $value => $count) {
    $typeSerie[] = [
        'name' => \prime\models\mapLayers\EventGrades::mapType($value),
        'count' => $count,
        'y' => round($count * 100 / array_sum($typeCount))
    ];
}

?>
<div class="row">
    <div class="col-xs-2 text-right"><h1><?=count($eventsResponses)?></h1></div>
    <div class="col-xs-10"><h3 style="line-height: 39px"><?=\Yii::t('app', 'Graded events')?></h3></div>
    <div class="col-sm-6"><h4 class="chart-head"><?=\Yii::t('app', 'grades')?></h4></div>
    <div class="col-sm-6"><h4 class="chart-head"><?=\Yii::t('app', 'types')?></h4></div>
    <?php
    echo \miloschuman\highcharts\Highcharts::widget([
        'options' => [
            'chart' => [
                'type' => 'pie',
                'height' => 295,
                'marginBottom' => 100,
                'spacingTop' => 0
            ],
            'title' => false,
            'series' => [
                [
                    'data' => $countSerie,
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'showInLegend' => true,
                    'tooltip' => [
                        'pointFormat' => '{point.count} ' . \Yii::t('app', 'Events') . '<br><b>{point.y}%</b><br/>'
                    ]
                ]
            ],
            'credits' => [
                'enabled' => false
            ]
        ],
        'htmlOptions' => [
            'class' => 'col-sm-6'
        ],
        'id' => 'events-grades'
    ]);
    ?>
    <?php
    echo \miloschuman\highcharts\Highcharts::widget([
        'options' => [
            'chart' => [
                'type' => 'pie',
                'height' => 295,
                'marginBottom' => 100,
                'spacingTop' => 0
            ],
            'title' => false,
            'series' => [
                [
                    'data' => $typeSerie,
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'tooltip' => [
                        'pointFormat' => '{point.count} ' . \Yii::t('app', 'Countries') . '<br><b>{point.y}%</b><br/>'
                    ],
                    'showInLegend' => true
                ]
            ],
            'credits' => [
                'enabled' => false
            ],
        ],
        'htmlOptions' => [
            'class' => 'col-sm-6'
        ],
        'id' => 'events-types'
    ]);
    ?>
</div>
