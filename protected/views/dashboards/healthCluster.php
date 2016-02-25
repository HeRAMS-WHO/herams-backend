<?php

use prime\models\mapLayers\CountryGrades;

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 * @var string $id
 * @var array $healthClustersResponses
 * @var string $layer
 * @var \prime\models\forms\MarketplaceFilter $filter
 */

$lastHealthClusterResponse = !empty($healthClustersResponses[$id]) ? $healthClustersResponses[$id][count($healthClustersResponses[$id]) - 1] : null;
?>

<div class="col-xs-8">
    <h1 style="margin-top: 0px;"><?=$lastHealthClusterResponse->getData()['CM00'] === 'A2' ? $lastHealthClusterResponse->getData()['LocalityID'] : $country->name?> / <?=\prime\models\mapLayers\HealthClusters::mapType($lastHealthClusterResponse->getData()['CM02'])?></h1>
</div>
<div class="col-xs-4">

</div>
<div class="col-xs-12">
    <?=$this->render('/marketplace/filter', ['filter' => $filter])?>
</div>
<div class="col-xs-12">
    <?=\yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('app', 'Coordination'),
                'content' => $this->render('country/healthClusters', ['healthClustersResponses' => $healthClustersResponses, 'id' => $id, 'filter' => $filter, 'popup' => $popup]),
                'visible' => !empty($healthClustersResponses),
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