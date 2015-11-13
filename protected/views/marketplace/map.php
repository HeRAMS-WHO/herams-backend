<?php

use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;

/**
 * @var \yii\web\View $this
 */

$this->params['containerClass'] = 'container-fluid';

$this->registerJs('Highcharts.maps["who/world"] = ' . file_get_contents(\Yii::getAlias('@app/data/countryPolygons/' . \prime\models\ar\Setting::get('countryPolygonsFile'))));
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
            'enabled' => true
        ],
        'plotOptions' => [
            'map' => [
                'allAreas' => false,
                'mapData' => new JsExpression('Highcharts.maps["who/world"]'),
            ]
        ],
        'series' => [
            (new \prime\models\MapLayer(['allAreas' => true]))->toArray(),
            (new \prime\models\mapLayers\Projects())->toArray()
        ],
        'credits' => [
            'enabled' => false
        ],
        'tooltip' => [
            'enabled' => false
        ],
        'height' => '600px'
    ],
    'setupOptions' => [
        'height' => '100%'
    ]
]);
$map->end();
$js = "
function select(point) {
    bootbox.alert(\"You selected \" + point.properties.CNTRY_TERR + \"!\");
}
";
$this->registerJs($js, $this::POS_BEGIN);
