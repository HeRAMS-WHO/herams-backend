<?php

use prime\models\mapLayers\EventGrades;

/**
 * @var \yii\web\View $this
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $eventResponses
 */

$serie = [];
foreach($eventResponses as $response) {
    $serie[] = [
        'grade' => EventGrades::mapGrade($response->getData()['GM02']),
        'y' => EventGrades::mapValue($response->getData()['GM02']),
        'color' => EventGrades::mapColor($response->getData()['GM02']),
        'name' => (new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y')
    ];
}
$lastResponse = $eventResponses[count($eventResponses) - 1];

echo \miloschuman\highcharts\Highcharts::widget([
    'options' => [
        'chart' => [
            'height' => 170,
            'marginTop' => 20
        ],
        'title' => false,
        'xAxis' => [
            'type' => 'category'
        ],
        'yAxis' => [
            'min' => 1,
            'max' => 4,
            'title' => false,
            'labels' => [
                'formatter' => new \yii\web\JsExpression(
                    'function(){' .
                    'var labelMap = ' . json_encode(EventGrades::gradeMap()) . ';' .
                    'var valueMap = ' . json_encode(array_flip(EventGrades::valueMap())) . ';' .
                    'return this.value > 1 ? labelMap[valueMap[this.value]] : "";' .
                    '}'
                )
            ],
            'tickInterval' => 1
        ],
        'series' => [
            [
                'data' => $serie,
                'lineWidth' => 0,
                'marker' => [
                    'lineWidth' => 2,
                    'lineColor' => '#A9A9A9',
                    'radius' => 17
                ],
                'tooltip' => [
                    'pointFormat' => '<b>{point.grade}</b><br/>'
                ]
            ]
        ],
        'legend' => [
            'enabled' => false
        ],
        'credits' => [
            'enabled' => false
        ]
    ],
    'htmlOptions' => [

    ],
    'id' => 'event_' . $lastResponse->getData()['UOID']
]);
?>