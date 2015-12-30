<?php

use prime\models\mapLayers\CountryGrades;

/**
 * @var \yii\web\View $this
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $countryResponses
 */

?>

<div class="row">
        <?php
        $serie = [];
        foreach($countryResponses as $response) {
            $serie[] = [
                'grade' => CountryGrades::mapGrade($response->getData()['GM02']),
                'y' => CountryGrades::mapValue($response->getData()['GM02']),
                'color' => CountryGrades::mapColor($response->getData()['GM02']),
                'name' => (new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y')
            ];
        }

        echo \miloschuman\highcharts\Highcharts::widget([
            'options' => [
                'chart' => [
                    'height' => 250,
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
                            'var labelMap = ' . json_encode(CountryGrades::gradeMap()) . ';' .
                            'var valueMap = ' . json_encode(array_flip(CountryGrades::valueMap())) . ';' .
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
                'class' => 'col-xs-12'
            ],
            'id' => 'country-grading'
        ]);
        ?>
</div>