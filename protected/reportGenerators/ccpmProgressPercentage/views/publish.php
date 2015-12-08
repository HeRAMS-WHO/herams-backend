<?php

/**
 * @var \yii\web\View $this
 */

$this->registerAssetBundle(\prime\assets\SassAsset::class);
$this->beginContent('@app/views/layouts/report.php');
?>
<style>
    body {
        margin-top:0px;
    }
</style>
<div class="container-fluid">
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
                            $responseRates['total']['responses'],
                            $responseRates['total']['total1']
                        ],
                        'dataLabels' => [
                            'enabled' => false
                        ]
                    ]],
                'credits' => ['enabled' => false],
                'tooltip' => [
                    'enabled' => false
                ],

            ],
            'view' => $this,
        ]);
        ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <h2><?=\Yii::t('app', 'Total response rate')?></h2>
        <?=app()->formatter->asPercent($responseRates['total']['responses'] / $responseRates['total']['total1']); ?>
        <h2><?=\Yii::t('app', 'Number partners responding')?></h2>
        <?=$responseRates['total']['responses'] ?>
        <h2><?=\Yii::t('app', 'Total number of partners')?></h2>
        <?=$responseRates['total']['total1']?>
    </div>
</div>
<?php $this->endContent(); ?>
</div>
