<?php

/**
 * @var \prime\widgets\progress\Absolute $widget
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
                            $widget->getPartnersResponding(),
                            $widget->getPartners() - $widget->getPartnersResponding()
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
    <div class="col-xs-12 col-md-6">
        <h2><?=\Yii::t('app', 'Total response rate')?></h2>
        <?=$widget->getResponseRate()?>%
        <h2><?=\Yii::t('app', 'Number partners responding')?></h2>
        <?=$widget->getPartnersResponding()?>
        <h2><?=\Yii::t('app', 'Total number of partners')?></h2>
        <?=$widget->getPartners()?>
    </div>
</div>