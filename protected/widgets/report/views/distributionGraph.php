<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 */

if(!empty($series[0]['data'])) {
    $chart = \miloschuman\highcharts\Highcharts::widget(
        [
            'options' => [
                'chart' => [
                    'type' => 'bar',
                    'backgroundColor' => 'transparent',
                    'marginLeft' => 400
                ],
                'title' => [
                    false
                ],
                'xAxis' => [
                    'type' => 'category',
                    'title' => false,
                ],
                'yAxis' => [
                    'title' => false,
                    'labels' => [
                        'formatter' => new \yii\web\JsExpression('function(){return (this.value * 100) + "%";}')
                    ]
                ],
                'series' => $series,
                'credits' => ['enabled' => false],
                'tooltip' => [
                    'enabled' => false
                ],
                'legend' => [
                    'enabled' => false
                ],

            ],
            'view' => $view,
            'htmlOptions' => [
                'style' => [
                    'height' => '350px'
                ]
            ]
        ]
    );
} else {
    $chart = '';
}

?>
<div class="row no-break">
    <?php if ($CQuestion != '') { ?>
    <div class="col-xs-12" style="height: 40px;"><strong><?=$title?></strong></div>
    <div class="col-xs-12"><?=\Yii::t('ccpm', 'Coordinator question')?></div>
    <div class="col-xs-12"><p style="font-style: italic; margin-top: 3px; margin-left: 20px;"><?=$CQuestion?></p></div>
    <div class="col-xs-12"><?=\Yii::t('ccpm', 'Coordinator answer')?></div>
    <div class="col-xs-12"><p style="font-style: italic; margin-top: 3px; margin-left: 20px;"><?=$CAnswer?></p></div>
    <?php } ?>

    <?php if ($PQuestion != '') { ?>
    <div class="col-xs-12"><?=\Yii::t('ccpm', 'Partners question')?></div>
    <div class="col-xs-12"><p style="font-style: italic; margin-top: 3px; margin-left: 20px;"><?=$PQuestion?></p></div>
    <div class="col-xs-12"><?=$chart != '' ? \Yii::t('ccpm', 'Partners distribution') : ''?></div>
    <div class="col-xs-12"><?=$chart?></div>
    <?php } ?>
</div>