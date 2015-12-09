<?php

use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;

/**
 * @var array $mapLayerData
 * @var \yii\web\View $this
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'List'),
        'url' => ['/marketplace/list'],
    ]
];

$this->params['containerOptions'] = ['class' => 'container-fluid'];
$this->params['rowOptions'] = ['class' => 'row'];

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.12/proj4-src.js');
$this->registerJs('Highcharts.maps["who/world"] = ' . file_get_contents(\Yii::getAlias('@app/data/countryPolygons/' . \prime\models\ar\Setting::get('countryPolygonsFile'))) . ';' .
    'Highcharts.maps["who/world"]["hc-transform"] = {default: {crs: "WGS84"}};'
);

$map = Highmaps::begin([
    'options' => [
        'title' => [
            'text' => false
        ],
        'mapNavigation' => [
            'enabled' => true,
            'buttonOptions' => [
                'verticalAlign' => 'bottom',
            ]
        ],
        'legend' => [
            'enabled' => true,
            'symbolHeight' => '0px'
        ],
        'plotOptions' => [
            'map' => [
                'allAreas' => false,
                'mapData' => new JsExpression('Highcharts.maps["who/world"]'),
            ]
        ],
        'series' => [
            \prime\factories\MapLayerFactory::get('base', [], ['allAreas' => true, 'nullColor' => "rgba(255, 255, 255, 0)"])->toArray(),
            \prime\factories\MapLayerFactory::get('projects', [$mapLayerData['projects']])->toArray(),
            \prime\factories\MapLayerFactory::get('countryGrades', [$mapLayerData['countryGrades']])->toArray(),
            \prime\factories\MapLayerFactory::get('eventGrades', [$mapLayerData['eventGrades']])->toArray(),
            \prime\factories\MapLayerFactory::get('healthClusters', [$mapLayerData['healthClusters']])->toArray(),
        ],
        'credits' => [
            'enabled' => false
        ],
        'tooltip' => [
            'enabled' => false,
        ],
        'chart' => [
            'height' => 600,
            'backgroundColor' => null
        ]
    ],
    'htmlOptions' => [
        'style' => [
            'bottom' => '0px'
        ],
        'class' => [
            'col-xs-12',
        ]
    ]
]);
$map->end();
echo \app\components\Html::tag('div', '', ['id' => 'legends', 'class' => 'col-xs-12', 'style' => ['text-align' => 'center']]);
$this->registerJsFile('/js/marketplace.js', ['depends' => [\yii\web\JqueryAsset::class, \prime\assets\BootBoxAsset::class]]);
