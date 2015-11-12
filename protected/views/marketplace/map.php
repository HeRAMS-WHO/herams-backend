<?php

use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;

/**
 * @var \yii\web\View $this
 */

$this->registerJs('Highcharts.maps["who/world"] = ' . file_get_contents(\Yii::getAlias('@webroot/json/WHO_CountryPolygons.json')));

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
//        'colorAxis' => [
//            'min' => 0,
//        ],
        'series' => [
            [
                'data' => [
                    ['ISO_3_CODE' => 'DEU', 'value' => 0],
                    ['ISO_3_CODE' => 'NLD', 'value' => 1]
                ],
                'mapData' => new JsExpression('Highcharts.maps["who/world"]'),
                'joinBy' => ['ISO_3_CODE'],
                'name' => 'Random data',
                'states' => [
                    'hover' => [
                        'color' => '#BADA55',
                    ]
                ],
                'dataLabels' => [
                    'enabled' => false,
                    //'format' => '{point.properties.CNTRY_TERR}',
                ],
                'showInLegend' => false,
                'allowPointSelect' => true,
                'point' => [
                    'events' => [
                        'select' => new JsExpression('function(e){select(this); return false;}')
                    ]
                ]
            ]
        ],
        'credits' => [
            'enabled' => false
        ],
        'tooltip' => [
            'enabled' => false
        ]
    ]
]);
$map->end();
$js = "
function select(point) {
    bootbox.alert(\"You selected \" + point.properties.CNTRY_TERR + \"!\");
}
";
$this->registerJs($js, $this::POS_BEGIN);
