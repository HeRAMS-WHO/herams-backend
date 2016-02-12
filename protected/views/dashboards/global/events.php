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
<div class="row">
    <?php
    echo Html::beginTag('table', [
        'class' => 'col-md-12 table print-1em',
        'style' => [
            'font-size' => '1.2em'
        ]

    ]);
    foreach($eventsResponses as $uoid => $eventResponses)  {
        echo '<tr>';

        $lastEventResponse = $eventResponses[count($eventResponses) - 1];
        $country = \prime\models\Country::findOne($lastEventResponse->getData()['PRIMEID']);

        echo Html::tag('td', Html::icon('stop', ['style' => [
            'color' => \prime\models\mapLayers\EventGrades::mapColor($lastEventResponse->getData()['GM02']) . ' !important',
        ]]));
        echo Html::tag('td', $country->name);
        echo Html::tag('td', $lastEventResponse->getData()['CED01']);
        echo Html::tag('td', \prime\models\mapLayers\EventGrades::mapGradingStage($lastEventResponse->getData()['GM00']));
        echo Html::tag('td', (new \Carbon\Carbon($lastEventResponse->getData()['GM01']))->format('d/m/Y'));
        echo '</tr>';
    }
    echo Html::endTag('table');
    ?>
</div>
