<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $countryResponses
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $eventResponses
 * @var \prime\models\mapLayers\CountryGrades $mapLayer
 */

$lastGradingResponse = $countryResponses[count($countryResponses) - 1];
$mapLayer = $this->context;
?>

<style>
    .timeline-block {
        text-align: center;
        border: 2px solid darkgrey;
        border-radius: 5px;
        height: 60px;
        padding-top: 16px;
        width: 100px;
        font-size: 1.3em;
        font-weight: bold;
    }
</style>

<div class="row">
    <div class="col-xs-8">
        <h2 style="margin-top: 0px;"><?=$country->name?></h2>
    </div>
    <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-12">
                <div class="timeline-block pull-right" style="background-color: <?=$mapLayer->mapColor($lastGradingResponse->getData()['GM02'])?>;">
                    <?=$mapLayer->mapGrade($lastGradingResponse->getData()['GM02'])?>
                </div>
                </div>
            <div class="col-xs-12" style="text-align: right">
                <?=$mapLayer->mapGradingStage($lastGradingResponse->getData()['GM00'])?><br>
                <?=(new \Carbon\Carbon($lastGradingResponse->getData()['GM01']))->format('d/m/Y')?>
            </div>
        </div>
    </div>
    <div class="col-xs-12"><h3><?=\Yii::t('app' , 'Grade monitoring')?></h3></div>
    <div class="col-xs-12">
        <?php
        $categories = [];
        $serie = [];
        foreach($countryResponses as $response) {
            $categories[] = (new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y');
            $serie[] = [
                'grade' => $mapLayer->mapGrade($response->getData()['GM02']),
                'y' => $mapLayer->mapValue($response->getData()['GM02']),
                'color' => (string) $mapLayer->mapColor($response->getData()['GM02']),
                'name' => (new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y')
            ];
        }

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
                            'var labelMap = ' . json_encode($mapLayer->gradeMap()) . ';' .
                            'var valueMap = ' . json_encode(array_flip($mapLayer->valueMap())) . ';' .
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
            'id' => 'timeline'
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12"><h3><?=\Yii::t('app', 'Graded events')?></h3></div>
    <?php
        foreach($eventResponses as $response) {
            ?>
            <div class="col-xs-12" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-xs-1">
                        <a href="<?=\yii\helpers\Url::to(['/marketplace/summary', 'layer' => 'eventGrades', 'id' => $response->getData()['UOID'], 'noMenu' => 1])?>"><div style="cursor: pointer; height: 34px; width: 34px; border: 2px solid darkgrey; border-radius: 50%; background-color: <?=\prime\models\mapLayers\EventGrades::mapColor($response->getData()['GM02'])?>;"></div></a>
                    </div>
                    <div class="col-xs-11" style="line-height: 34px">
                        <?=$response->getData()['CED01']?> / <?=\prime\models\mapLayers\EventGrades::mapGrade($response->getData()['GM02'])?> / <?=\prime\models\mapLayers\EventGrades::mapGradingStage($response->getData()['GM00'])?> (<?=(new \Carbon\Carbon($response->getData()['GM01']))->format('d/m/Y')?>)
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>