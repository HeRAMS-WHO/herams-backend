<?php

use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;

/**
 * @var \yii\web\View $this
 */

$this->registerJsFile('http://code.highcharts.com/mapdata/custom/world.js', [
    'depends' => 'miloschuman\highcharts\HighchartsAsset'
]);

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
                    ['iso-a3' => 'DEU', 'value' => 0],
                    ['iso-a3' => 'NLD', 'value' => 1]
                ],
                'mapData' => new JsExpression('Highcharts.maps["custom/world"]'),
                'joinBy' => ['iso-a3'],
                'name' => 'Random data',
                'states' => [
                    'hover' => [
                        'color' => '#BADA55',
                    ]
                ],
                'dataLabels' => [
                    'enabled' => false,
                    'format' => '{point.name}',
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
        ]
    ]
]);
$map->end();
$js = "
function select(point) {
    bootbox.alert(\"You selected \" + point.name + \"!\");
}
";
$this->registerJs($js, $this::POS_BEGIN);
