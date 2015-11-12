<?php

/**
 * @var \prime\reportGenerators\progressPercentage\Report $report
 * @var \yii\web\View $this
 */
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?=\Yii::t('app', 'Progress')?></h1>
    </div>
    <div class="col-xs-12 col-md-6">
        <?php
        echo \miloschuman\highcharts\Highcharts::widget([
            'options' => [
                'chart' => [
                    'type' => 'pie',
                    'backgroundColor' => 'transparent',
                ],
                'title' => [
                    false
                ],
                'yAxis' => ['title' => false],
                'series' => [
                    [
                        'name' => \Yii::t('app', 'Statuses'),
                        'data' => [
                            $report->getPartnersResponding(),
                            $report->getPartners() - $report->getPartnersResponding()
                        ],
                        'dataLabels' => [
                            'enabled' => false
                        ]
                    ]],
                'credits' => ['enabled' => false],
                'tooltip' => [
                    'enabled' => false
                ]
            ]
        ])
        ?>
    </div>
    <style>
        #stats:hover {
            height: 800px;
        }
    </style>
    <div class="col-xs-12 col-md-6" id="stats">
        <h2><?=\Yii::t('app', 'Total response rate')?></h2>
        <?=$report->getResponseRate()?>%
        <h2><?=\Yii::t('app', 'Number partners responding')?></h2>
        <?=$report->getPartnersResponding()?>
        <h2><?=\Yii::t('app', 'Total number of partners')?></h2>
        <?=$report->getPartners()?>
    </div>
</div>