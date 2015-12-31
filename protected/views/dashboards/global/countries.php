<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var array $countriesResponses
 */

//filter responses
$tempData = [];
foreach($countriesResponses as $iso3 => $countryResponses) {
    $lastCountryResponse = $countryResponses[count($countryResponses) - 1];
    if($lastCountryResponse->getData()['GM02'] == 'A0' || $lastCountryResponse->getData()['GM02'] == '') {
        unset($countriesResponses[$iso3]);
        continue;
    }

    if(!isset($tempData[$lastCountryResponse->getData()['GM02']])) {
        $tempData[$lastCountryResponse->getData()['GM02']] = 0;
    }

    $tempData[$lastCountryResponse->getData()['GM02']]++;
}

ksort($tempData);
$serie = [];
foreach($tempData as $value => $count) {
    $serie[] = [
        'name' => \prime\models\mapLayers\CountryGrades::mapGrade($value),
        'color' => \prime\models\mapLayers\CountryGrades::mapColor($value),
        'count' => $count,
        'y' => round($count * 100 / array_sum($tempData))
    ];
}

?>
<div class="row">
    <div class="col-xs-2 text-right"><h1><?=count($countriesResponses)?></h1></div>
    <div class="col-xs-10"><h3 style="line-height: 39px"><?=\Yii::t('app', 'Graded countries')?></h3></div>
    <div class="col-sm-6 col-sm-offset-3"><h4 class="chart-head"><?=\Yii::t('app', 'grades')?></h4></div>
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
                    'data' => $serie,
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
            ]
        ],
        'htmlOptions' => [
            'class' => 'col-sm-6 col-sm-offset-3'
        ],
        'id' => 'countries'
    ]);
    ?>
</div>
