<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $gradingResponses
 * @var array $eventsResponses
 * @var array $healthClustersResponses
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 */

?>
<style>
    .overview-el {
        border: 2px solid darkgrey;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 30px;
    }

    .overview-el > h3 {
        margin-top: 0px;
    }
</style>
<div class="row">
    <div class="col-xs-6">
        <div class="overview-el">
            <h3><?=\Yii::t('app', 'Active projects')?></h3>
            <h1 style="text-align: center;"><?=$projectsDataProvider->query->copy()->count()?></h1>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="overview-el">
            <h3><?=\Yii::t('app', 'Events')?></h3>
            <h1 style="text-align: center;"><?=count($eventsResponses)?></h1>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="overview-el">
            <h3><?=\Yii::t('app', 'Health Clusters')?></h3>
            <h1 style="text-align: center;"><?=count($healthClustersResponses)?></h1>
        </div>
    </div>
</div>
