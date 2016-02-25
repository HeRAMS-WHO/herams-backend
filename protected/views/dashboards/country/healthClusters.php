<?php

use app\components\Html;
use prime\models\mapLayers\HealthClusters;

/**
 * @var \yii\web\View $this
 * @var string $id
 * @var array $healthClustersResponses
 * @var \prime\models\forms\MarketplaceFilter $filter
 */

$this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);

echo Html::beginTag('div', ['class' => 'row', 'style' => ['overflow-y' => 'auto', 'max-height' => '340px']]);

//if not isset($id), than it is a country dashboard, pick the response group that is national

foreach($healthClustersResponses as $uoid => $responses) {
    if($responses[0]->getData()['CM00'] == 'A1') {
        $nationalClusterId = $uoid;
        break;
    }
}

if(!isset($id) && isset($nationalClusterId)) {
    $id = $nationalClusterId;
}

$subnational = isset($id) ? ($healthClustersResponses[$id][0]->getData()['CM00'] == 'A2') : false;

//it could be that there is no "current" cluster to show (example, country dashboard with no national cluster but subnational clusters)
if(isset($id)) {
    $currentHealthClusterResponses = $healthClustersResponses[$id];
    $lastHealthClusterResponse = $currentHealthClusterResponses[count($currentHealthClusterResponses) - 1];
    $project = \prime\models\ar\Project::findOne(\prime\models\ar\Setting::get('healthClusterDashboardProject'));
    ?>
    <style>
        iframe {
            width: 100%;
            border: 0px;
            overflow-y: hidden;
        }
    </style>
    <iframe
        src="<?=\yii\helpers\Url::to(['/reports/render-preview',
            'projectId' => $project->id,
            'reportGenerator' => $project->default_generator,
            'responseId' => $lastHealthClusterResponse->getData()['id']
        ])?>"
        class="resize"
    ></iframe>
    <?php
}

echo Html::beginTag('div', ['class' => ['col-xs-12']]);
if($subnational) {
    echo Html::tag('h4', \Yii::t('app', 'National coordination structure'));
    if(isset($nationalClusterId)) {
        $lastNationalResponse = $healthClustersResponses[$nationalClusterId][count($healthClustersResponses[$nationalClusterId]) - 1];
        $country = \prime\models\Country::findOne($lastNationalResponse->getData()['PRIMEID']);
        echo Html::a(
            $country->name . ' / ' . HealthClusters::mapType($lastNationalResponse->getData()['CM02']),
            [
                '/marketplace/country-dashboard',
                'MarketplaceFilter' => $filter->getAttributes(),
                'iso_3' => $lastNationalResponse->getData()['PRIMEID'],
                'layer' => 'healthClusters',
                'popup' => $popup
            ]
        );
    }
} else {
    echo Html::tag('h4', \Yii::t('app', 'Subnational coordination structures'));
    foreach($healthClustersResponses as $uoid => $responses) {
        if(!isset($id) || $uoid != $id) {
            $lastSubationalResponse = $responses[count($responses) - 1];
            echo Html::a(
                $lastSubationalResponse->getData()['LocalityID'] . ' / ' . HealthClusters::mapType($lastSubationalResponse->getData()['CM02']),
                [
                    '/marketplace/health-cluster-dashboard',
                    'MarketplaceFilter' => $filter->getAttributes(),
                    'iso_3' => $lastSubationalResponse->getData()['PRIMEID'],
                    'layer' => 'healthClusters',
                    'id' => $uoid,
                    'popup' => $popup
                ]
            ) . '<br>';
        }
    }
}
echo Html::endTag('div');

echo Html::endTag('div');