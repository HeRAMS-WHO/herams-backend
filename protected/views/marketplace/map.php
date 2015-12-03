<?php

use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;

/**
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
//vdd((new \prime\models\mapLayers\Projects())->toArray());
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
            (new \prime\models\MapLayer(['allAreas' => true, 'nullColor' => "rgba(255, 255, 255, 0)"]))->toArray(),
            (new \prime\models\mapLayers\Projects())->toArray(),
            (new \prime\models\mapLayers\CountryGrades())->toArray(),
            (new \prime\models\mapLayers\EventGrades())->toArray(),
            (new \prime\models\mapLayers\HealthClusters())->toArray(),
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
$this->registerJsFile('/js/marketplace.js', ['depends' => [\yii\web\JqueryAsset::class, \prime\assets\BootBoxAsset::class]]);
