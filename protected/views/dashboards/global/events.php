<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var array $eventsResponses
 */

//filter responses
$eventCount = [];
$typeCount = [];
$regionCount = [];
$countryCount = [];
foreach($eventsResponses as $uoid => $eventResponses) {
    $lastEventResponse = $eventResponses[count($eventResponses) - 1];
    $country = \prime\models\Country::findOne($lastEventResponse->getData()['PRIMEID']);
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

    if (!isset($countryCount[$country->iso_3])) {
        $countryCount[$country->iso_3] = 0;
    }

    if(!isset($regionCount[$country->region])) {
        $regionCount[$country->region] = 0;
    }

    $eventCount[$lastEventResponse->getData()['GM02']]++;
    $typeCount[$lastEventResponse->getData()['CED02']]++;
    $countryCount[$country->iso_3]++;
    $regionCount[$country->region]++;
}

ksort($eventCount);
arsort($typeCount);
arsort($regionCount);
$countSerie = [];
foreach($eventCount as $value => $count) {
    $countSerie[] = [
        'name' => \prime\models\mapLayers\EventGrades::mapGrade($value),
        'color' => (string) \prime\models\mapLayers\EventGrades::mapColor($value),
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

$regionSerie = [];
foreach($regionCount as $value => $count) {
    $regionSerie[] = [
        'name' => $value,
        'count' => $count,
        'y' => round($count * 100 / array_sum($regionCount))
    ];
}

$this->registerJs(<<<JS
    var monochromeColors = (function () {
        var colors = [],
            base = Highcharts.getOptions().colors[0],
            i;

        for (i = 0; i < 10; i += 1) {
            // Start out with a darkened base color (negative brighten), and end
            // up with a much brighter color
            colors.push(Highcharts.Color(base).brighten((i - 4) / 8).get());
        }
        return colors;
    }());
JS
)

?>
<style>
    <?php
     /** @var \prime\objects\Color $color */
    foreach(\prime\models\mapLayers\EventGrades::colorMap() as $value => $color) {
    ?>
    .table-striped>tbody>tr.event-grade-color-<?=$value?>:nth-child(odd) {
        background-color: <?=$color->lighten(40)?>;
    }

    .table-striped>tbody>tr.event-grade-color-<?=$value?>:nth-child(even) {
        background-color: <?=$color->lighten(45)?>;
    }
    <?php } ?>
</style>
<div class="row">
    <?=$this->render('greyHeader')?>
    <div class="col-md-5">
        <span class="h3"><span class="h1"><?=count($eventsResponses)?></span> <?=\Yii::t('app', 'Graded events in')?>
            <span class="h1"><?=count($countryCount)?></span> <?=\Yii::t('app', 'graded countries')?></span>
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
                        'data' => $typeSerie,
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
                'plotOptions' => [
                    'pie' => [
                    'colors' => new \yii\web\JsExpression('monochromeColors')
                    ]
                ],
                'credits' => [
                    'enabled' => false
                ],
            ],
            'id' => 'events-types'
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
                        'data' => $countSerie,
                        'dataLabels' => [
                            'enabled' => false
                        ],
                        'showInLegend' => true,
                        'tooltip' => [
                            'pointFormat' => '{point.count} ' . \Yii::t('app', 'Events') . '<br><b>{point.y}%</b><br/>'
                        ],
                        'innerSize' => '50%',
                    ]
                ],
                'credits' => [
                    'enabled' => false
                ]
            ],
            'id' => 'events-grades'
        ]);
        ?>
        <span class="h3"><?=\Yii::t('app', 'Regional distribution of graded events')?></span>
        <?php
        echo \miloschuman\highcharts\Highcharts::widget([
            'options' => [
                'chart' => [
                    'type' => 'pie',
                    'height' => 350,
                    'marginBottom' => 80,
                    'spacingTop' => 0
                ],
                'title' => [
                    'text' => null,
                ],
                'series' => [
                    [
                        'data' => $regionSerie,
                        'dataLabels' => [
                            'enabled' => false
                        ],
                        'tooltip' => [
                            'pointFormat' => '{point.count} ' . \Yii::t('app', 'Events') . '<br><b>{point.y}%</b><br/>'
                        ],
                        'innerSize' => '50%',
                        'showInLegend' => true
                    ]
                ],
                'credits' => [
                    'enabled' => false
                ]
            ],
            'id' => 'events-regions'
        ]);
        ?>
    </div>

    <div class="col-md-7">
    <?php
    echo Html::beginTag('table', [
        'class' => 'table table-condensed table-striped print-1em',
        'style' => [
            //'font-size' => '1.2em'
        ]

    ]);
    ?>
        <thead>
        <tr>
            <th style="width: 35px;"></th>
            <th><?=Yii::t('app', 'Graded country'); ?></th>
            <th><?=Yii::t('app', 'Event'); ?></th>
            <th><?=Yii::t('app', 'Grading stage'); ?></th>
            <th><?=Yii::t('app', 'Date'); ?></th>
        </tr>
        </thead>
    <?php
    foreach($eventsResponses as $uoid => $eventResponses)  {
        $lastEventResponse = $eventResponses[count($eventResponses) - 1];
        $country = \prime\models\Country::findOne($lastEventResponse->getData()['PRIMEID']);

        echo '<tr class="event-grade-color-' . $lastEventResponse->getData()['GM02'] . '">';

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
    <?=$this->render('greyFooter');?>
</div>
