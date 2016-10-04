<?php

use prime\models\mapLayers\CountryGrades;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var array $countriesResponses
 * @var array $eventsResponses
 * @var array $healthClustersResponses
 * @var string $layer
 * @var \prime\models\forms\MarketplaceFilter $filter
 */

//$this->params['containerOptions'] = ['class' => 'container-fluid'];
?>

<style>
    h4.chart-head {
        margin: 0px 0px 3px 0px;
        text-align: center;
    }

    .highcharts-container {
        margin-bottom: 20px;
    }
</style>

<div class="col-xs-12">
    <h1 style="margin-top: 0px; margin-bottom: 20px;"><?=\Yii::t('app', 'Global dashboard')?></h1>
</div>
<div class="col-xs-12">
    <?=$this->render('/marketplace/filter', ['filter' => $filter])?>
</div>
<div class="col-xs-12">
    <?=\kartik\tabs\TabsX::widget([
        'printable' => true,
        'items' => [
            [
                'label' => \Yii::t('app', 'Graded countries / territories'),
                'content' => $this->render('global/countries', ['countriesResponses' => $countriesResponses]),
                'active' => $layer == 'countryGrades'
            ],
            [
                'label' => \Yii::t('app', 'Graded events'),
                'content' => $this->render('global/events', ['eventsResponses' => $eventsResponses]),
                'active' => $layer == 'eventGrades'
            ],
            [
                'label' => \Yii::t('app', 'Coordination'),
                'content' => $this->render('global/healthClusters', [
                    'healthClustersResponses' => $healthClustersResponses,
                    'eventsResponses' => $eventsResponses,
                    'countriesResponses' => $countriesResponses
                ]),
                'active' => $layer == 'healthClusters'
            ],
        ],
        'options' => [
            'style' => [
                'margin-bottom' => '10px'
            ]
        ]
    ])?>
</div>