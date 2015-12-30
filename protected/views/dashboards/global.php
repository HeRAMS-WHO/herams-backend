<?php

use prime\models\mapLayers\CountryGrades;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var array $countriesResponses
 * @var array $eventsResponses
 * @var array $healthClustersResponses
 * @var string $layer
 */

?>

<div class="row">
    <div class="col-xs-12">
        <h1 style="margin-top: 0px; margin-bottom: 20px;"><?=\Yii::t('app', 'Global dashboard')?></h1>
    </div>
    <div class="col-xs-12">
        <?=\yii\bootstrap\Tabs::widget([
            'items' => [
                [
                    'label' => \Yii::t('app', 'Country grades'),
                    'content' => $this->render('global/countries', ['countriesResponses' => $countriesResponses]),
                    'active' => $layer == 'countryGrades'
                ],
//                [
//                    'label' => \Yii::t('app', 'Events'),
//                    'content' => $this->render('country/events', ['eventsResponses' => $eventsResponses]),
//                    'visible' => !empty($eventsResponses),
//                    'active' => $layer == 'eventGrades'
//                ],
//                [
//                    'label' => \Yii::t('app', 'Health Clusters'),
//                    'content' => $this->render('country/healthClusters', ['healthClustersResponses' => $healthClustersResponses]),
//                    'visible' => !empty($healthClustersResponses),
//                    'active' => $layer == 'healthClusters'
//                ]
            ],
            'options' => [
                'style' => [
                    'margin-bottom' => '10px'
                ]
            ]
        ])?>
    </div>
</div>