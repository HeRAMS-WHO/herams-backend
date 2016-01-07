<?php

/**
 * @var \yii\web\View $this
 */

echo \miloschuman\highcharts\Highcharts::widget([
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
                'data' => [
                    [
                        'y' => (int) $percentage
                    ],
                    [
                        'y' => 100 - $percentage,
                        'dataLabels' => [
                            'enabled' => false
                        ]
                    ],
                ],
                'colors' => [
                    '#EC781C',
                    '#DDDDDD'
                ],
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{y}%',
                ],
                'animation' => false
            ]
        ],
        'plotOptions' => [
            'pie' => [
                'size' => 130
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
            'height' => '160px'
        ]
    ]
]);
?>