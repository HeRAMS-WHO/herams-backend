<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\widgets\report\GraphWithNumbers $widget
 */

$widget = $this->context;

$chart = \miloschuman\highcharts\Highcharts::widget([
    'options' => [
        'chart' => [
            'type' => 'pie',
            'backgroundColor' => 'transparent'
        ],
        'title' => [
            false
        ],
        'yAxis' => ['title' => false],
        'series' => [
            [
                'name' => \Yii::t('app', 'Statuses'),
                'data' => [
                    $widget->part,
                    max($widget->total - $widget->part, 0)
                ],
                'colors' => [
                    '#EC781C',
                    '#F5B161'
                ],
                'dataLabels' => [
                    'enabled' => false
                ],
                'animation' => false
            ]
        ],
        'credits' => ['enabled' => false],
        'tooltip' => [
            'enabled' => false
        ]
    ],
    'view' => $this,
    'htmlOptions' => [
        'style' => [
            //@TODO fix if this issue is fixed
            //https://github.com/highcharts/highcharts/issues/1758
            'height' => (($widget->graphWidth / 4) * 170) .'px'
        ]
    ]
]);

?>
<div class="row">
    <div class="col-xs-12"><?=$widget->title?></div>
    <div class="col-xs-12">
        <?=\prime\widgets\report\Columns::widget([
            'items' => [
                [
                    'content' => $chart,
                    'width' => $widget->graphWidth
                ],
                [
                    'columns' => [
                        'items' => [
                            [
                                'content' => \prime\widgets\report\Block::widget(['items' => [$widget->texts['top'], ['content' => (($widget->total > 0) ? round(($widget->part / $widget->total) * 100) : 0) . ' %', 'htmlOptions' => ['class' => 'text-medium']]]]),
                                'width' => 2
                            ],
                            \prime\widgets\report\Block::widget(['items' => [$widget->texts['left'], ['content' => $widget->total, 'htmlOptions' => ['class' => '']]]]),
                            \prime\widgets\report\Block::widget(['items' => [$widget->texts['right'], ['content' => $widget->part, 'htmlOptions' => ['class' => '']]]])

                        ],
                        'columnsInRow' => 2
                    ],
                    'width' => 6
                ]
            ],
            'columnsInRow' => $widget->graphWidth + 6
        ])?>
    </div>
</div>