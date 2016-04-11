<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var array $countriesResponses
 */

//filter responses
$countryDistribution = [];
$regionDistribution = [];
foreach($countriesResponses as $iso3 => $countryResponses) {
    $country = \prime\models\Country::findOne($iso3);
    $lastCountryResponse = $countryResponses[count($countryResponses) - 1];
    if($lastCountryResponse->getData()['GM02'] == 'A0' || $lastCountryResponse->getData()['GM02'] == '') {
        unset($countriesResponses[$iso3]);
        continue;
    }

    if(!isset($countryDistribution[$lastCountryResponse->getData()['GM02']])) {
        $countryDistribution[$lastCountryResponse->getData()['GM02']] = 0;
    }

    if(!isset($regionDistribution[$country->region])) {
        $regionDistribution[$country->region] = 0;
    }

    $countryDistribution[$lastCountryResponse->getData()['GM02']]++;
    $regionDistribution[$country->region]++;
}

ksort($countryDistribution);
$gradeSerie = [];
foreach($countryDistribution as $value => $count) {
    $gradeSerie[] = [
        'name' => \prime\models\mapLayers\CountryGrades::mapGrade($value),
        'color' => (string) \prime\models\mapLayers\CountryGrades::mapColor($value),
        'count' => $count,
        'y' => round($count * 100 / array_sum($countryDistribution))
    ];
}

arsort($regionDistribution);
$regionSerie = [];
foreach($regionDistribution as $value => $count) {
    $regionSerie[] = [
        'name' => $value,
        'count' => $count,
        'y' => round($count * 100 / array_sum($regionDistribution))
    ];
}

?>
<style>
    <?php
     /** @var \prime\objects\Color $color */
    foreach(\prime\models\mapLayers\CountryGrades::colorMap() as $value => $color) {
    ?>
    .table-striped>tbody>tr.country-grade-color-<?=$value?>:nth-child(odd) {
        background-color: <?=$color->lighten(40)?>;
    }

    .table-striped>tbody>tr.country-grade-color-<?=$value?>:nth-child(even) {
        background-color: <?=$color->lighten(45)?>;
    }
    <?php } ?>
</style>
<div class="row">
    <?=$this->render('greyHeader')?>
    <div class="col-md-5">
        <span class="h3"><span class="h1"><?=count($countriesResponses)?></span> <?=\Yii::t('app', 'Graded/protracted* countries')?></span>
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
                        'data' => $gradeSerie,
                        'dataLabels' => [
                            'enabled' => false
                        ],
                        'tooltip' => [
                            'pointFormat' => '{point.count} ' . \Yii::t('app', 'Countries') . '<br><b>{point.y}%</b><br/>'
                        ],
                        'innerSize' => '50%',
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
        <span class="h3"><?=\Yii::t('app', 'Regional distribution of graded/protracted* countries')?></span>
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
                            'pointFormat' => '{point.count} ' . \Yii::t('app', 'Countries') . '<br><b>{point.y}%</b><br/>'
                        ],
                        'innerSize' => '50%',
                        'showInLegend' => true
                    ]
                ],
                'credits' => [
                    'enabled' => false
                ]
            ],
            'htmlOptions' => [
            ],
            'id' => 'regions'
        ]);
        ?>
        <small style="color: #a1a1a1;"><i>*<?=\Yii::t('app', 'protracted countries: countries with an HRP or parth of the 3RP that are not otherwise graded')?></i></small>
    </div>

    <?php
        echo Html::beginTag('div', [
            'class' => 'col-md-7',
        ]);
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
        <th><?=Yii::t('app', 'Grading stage'); ?></th>
        <th><?=Yii::t('app', 'Date'); ?></th>
    </tr>
    </thead>
    <?php
    $protractedCountries = [];
foreach($countriesResponses as $iso_3 => $countryResponses)  {
    $lastCountryResponse = $countryResponses[count($countryResponses) - 1];
    $country = \prime\models\Country::findOne($iso_3);

    if($lastCountryResponse->getData()['GM02'] == 'A4') {
        $protractedCountries[] = $country;
        continue;
    }

    echo '<tr class="country-grade-color-' . $lastCountryResponse->getData()['GM02'] . '">';
    echo Html::tag('td', Html::icon('stop', ['style' => [
        'color' => \prime\models\mapLayers\CountryGrades::mapColor($lastCountryResponse->getData()['GM02']) . ' !important',
    ]]));
    echo Html::tag('td', $country->name);
    echo Html::tag('td', \prime\models\mapLayers\CountryGrades::mapGradingStage($lastCountryResponse->getData()['GM00']));
    echo Html::tag('td', (new \Carbon\Carbon($lastCountryResponse->getData()['GM01']))->format('d/m/Y'));
    echo '</tr>';
}
echo Html::endTag('table');

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
        <th><?=Yii::t('app', 'Protracted country'); ?></th>
        <th style="width: 35px"></th>
        <th></th>
    </tr>
    </thead>
    <?php
    $i = 0;
    foreach($protractedCountries as $country)  {
        if($i % 2 == 0) {
            echo '<tr>';
        }

        echo Html::tag('td', Html::icon('stop', ['style' => [
            'color' => \prime\models\mapLayers\CountryGrades::mapColor('A4') . ' !important',
        ]]));

        echo Html::tag('td', $country->name);

        $i++;

        if($i % 2 == 0) {
            echo '</tr>';
        }
    }
    if($i % 2 == 1) {
        echo '<td></td><td></td></tr>';
    }
    echo Html::endTag('table');


echo Html::endTag('div');



    echo $this->render('greyFooter');
?>
</div>