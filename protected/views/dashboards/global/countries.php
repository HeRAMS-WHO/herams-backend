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
    <div class="col-md-5 col-sm-12">
    <div class="h3"><span class="h1"><?=count($countriesResponses)?></span> <?=\Yii::t('app', 'Graded countries')?></div>
<?php
echo \miloschuman\highcharts\Highcharts::widget([
    'options' => [
        'chart' => [
            'type' => 'pie',
            'height' => 295,
            'marginBottom' => 100,
            'spacingTop' => 0
        ],
        'title' => [
            'text' => null,
        ],
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
    ],
    'id' => 'countries'
]);
?>
    </div><?php
echo Html::beginTag('div', [
    'class' => 'col-sm-12 col-md-7',
]);
echo Html::beginTag('table', [
    'class' => 'table print-1em',
    'style' => [
        'font-size' => '1.2em'
    ]

]);
    ?>
    <thead>
    <tr>
        <th></th>
        <th><?=Yii::t('app', 'Graded country'); ?></th>
        <th><?=Yii::t('app', 'Event'); ?></th>
<!--        <th>--><?//=Yii::t('app', 'Stage'); ?><!--</th>-->
        <th><?=Yii::t('app', 'Date'); ?></th>
    </tr>
    </thead>
    <?php
foreach($countriesResponses as $iso_3 => $countryResponses)  {
    echo '<tr>';
    $lastCountryResponse = $countryResponses[count($countryResponses) - 1];
    $country = \prime\models\Country::findOne($iso_3);
    echo Html::tag('td', Html::icon('stop', ['style' => [
        'color' => \prime\models\mapLayers\CountryGrades::mapColor($lastCountryResponse->getData()['GM02']) . ' !important',
    ]]));
    echo Html::tag('td', $country->name);
    echo Html::tag('td', \prime\models\mapLayers\CountryGrades::mapGradingStage($lastCountryResponse->getData()['GM00']));
    echo Html::tag('td', (new \Carbon\Carbon($lastCountryResponse->getData()['GM01']))->format('d/m/Y'));
    echo '</tr>';
}
echo Html::endTag('table');
echo Html::endTag('div');
?>
</div>