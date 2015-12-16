<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 */

$chart = \miloschuman\highcharts\Highcharts::widget([
    'options' => [
        'chart' => [
            'backgroundColor' => 'transparent'
        ],
        'title' => [
            false
        ],
        'yAxis' => [
            'title' => false,
            'max' => 1
        ],
        'series' => $series,
        'credits' => ['enabled' => false],
        'tooltip' => [
            'enabled' => false
        ],
        'legend' => [
            'enabled' => false
        ]
    ],
    'view' => $view,
    'htmlOptions' => [
        'style' => [
            'height' => '170px'
        ]
    ]
]);

?>
<div class="row no-break">
    <div class="col-xs-12" style="height: 40px;"><?=$title?></div>
    <div class="col-xs-12"><?=$chart?></div>
</div>