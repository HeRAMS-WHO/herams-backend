<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\widgets\report\FunctionAndReview $widget
 */

$widget = $this->context;
$middleColumn = Html::tag('h4', $widget->title) . '<hr>';

$rows = [];
foreach($widget->scores as $key => $value) {
    $rows[] = ['cells' => [$key, $value]];
}
$middleColumn .= \prime\widgets\report\Table::widget([
    'rows' => $rows,
    'columnOptions' => [
        [
            'style' => ['width' => '90%']
        ],
        [
            'style' => ['width' => '10%', 'text-align' => 'right']
        ]
    ],
    'options' => [
        'style' => ['width' => '100%'],
        'class' => ['table-striped']
    ]
]);

foreach($widget->notes as $key => $value) {
    $middleColumn .= '<hr>';
    $middleColumn .= \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => $key,
                'width' => 3
            ],
            [
                'content' => $value,
                'width' => 7
            ]
        ],
        'columnsInRow' => 10
    ]);
}

echo \prime\widgets\report\Columns::widget([
    'items' => [
        [
            'content' => $widget->number,
            'options' => [
                'style' => [
                    'text-align' => 'right'
                ]
            ]
        ],
        [
            'content' => $middleColumn,
            'width' => 9
        ],
        [
            'content' => "<span class='background-{$widget->score}' style='display: block; padding-top: 5px; padding-bottom: 5px; text-align: center'>" . ucfirst($widget->score) . "</span>",
            'width' => 2
        ]

    ],
    'columnsInRow' => 12
]);