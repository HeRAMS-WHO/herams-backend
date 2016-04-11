<?php

/**
 * @var \yii\web\View $this
 * @var \prime\interfaces\SurveyCollectionInterface $surveys
 * @var array $responseRates
 * @var \prime\interfaces\ProjectInterface $project
 * @var \SamIT\LimeSurvey\JsonRpc\Client $limeSurvey
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
        <h3><?=\Yii::t('app', 'Progress')?></h3>
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
                        'colors' => [
                            '#EC781C',
                            '#F5B161'
                        ],
                        'dataLabels' => [
                            'enabled' => false
                        ],

                    ]
                ],
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
        <style>
            .progress-value > h2{
                font-size: 2.5em;
                margin-bottom: 0px;
            }

            .progress-title > span {
                position: absolute;
                bottom: 0px;
                font-size: 1.2em;
            }

        </style>
        <div class="col-xs-hidden" style="height: 80px;"></div>
        <div class="row row-eq-height">
            <div class="col-sm-2 col-sm-offset-0 col-xs-2 col-xs-offset-3 progress-value">
                <h2><?=app()->formatter->asPercent($responseRates['total']['total1'] > 0 ? $responseRates['total']['responses'] / $responseRates['total']['total1'] : 0); ?></h2>
            </div>
            <div class="col-sm-10 col-xs-7 progress-title">
                <span><?=\Yii::t('app', 'Total response rate')?></span>
            </div>
        </div>
        <div class="row row-eq-height">
            <div class="col-sm-2 col-sm-offset-0 col-xs-2 col-xs-offset-3 progress-value">
                <h2><?=$responseRates['total']['responses']?></h2>
            </div>
            <div class="col-sm-10 col-xs-7 progress-title">
                <span><?=\Yii::t('app', 'Number partners responding')?></span>
            </div>
        </div>
        <div class="row row-eq-height">
            <div class="col-sm-2 col-sm-offset-0 col-xs-2 col-xs-offset-3 progress-value">
                <h2><?=$responseRates['total']['total1']?></h2>
            </div>
            <div class="col-sm-10 col-xs-7 progress-title">
                <span><?=\Yii::t('app', 'Total number of partners')?></span>
            </div>
        </div>
    </div>
<!--    <div class="col-xs-12">-->
<!--        <table class="table">-->
            <?php
//            /** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */
//            foreach($surveys as $survey) {
//                $url = \app\components\Html::a($limeSurvey->getUrl(
//                    $survey->getId(),
//                    [
//                        'token' => $project->getAttribute('token'),
//                        'newtest' => 'Y'
//                    ]
//                ));
//                echo "<tr><td>{$survey->getTitle()}</td><td>{$url}</td></tr>";
//            }
            ?>
<!--        </table>-->
<!--    </div>-->
</div>
</div>
<?php $this->endContent(); ?>