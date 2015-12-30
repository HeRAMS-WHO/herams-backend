<?php

use prime\models\mapLayers\CountryGrades;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $projectsDataProvider
 * @var array $gradingResponses
 * @var array $eventsResponses
 * @var array $healthClustersResponses
 * @var string $layer
 */

$lastGradingResponse = !empty($gradingResponses) ? $gradingResponses[count($gradingResponses) - 1] : null;
?>

<style>
    .timeline-block {
        text-align: center;
        border: 2px solid darkgrey;
        border-radius: 5px;
        height: 60px;
        padding-top: 16px;
        width: 100px;
        font-size: 1.3em;
        font-weight: bold;
    }
</style>

<div class="row">
    <div class="col-xs-8">
        <h1 style="margin-top: 0px;"><?=$country->name?></h1>
    </div>
    <div class="col-xs-4">
        <?php if(isset($lastGradingResponse)) { ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="timeline-block pull-right" style="background-color: <?=CountryGrades::mapColor($lastGradingResponse->getData()['GM02'])?>;">
                    <?=CountryGrades::mapGrade($lastGradingResponse->getData()['GM02'])?>
                </div>
            </div>
            <div class="col-xs-12" style="text-align: right">
                <?=CountryGrades::mapGradingStage($lastGradingResponse->getData()['GM00'])?><br>
                <?=(new \Carbon\Carbon($lastGradingResponse->getData()['GM01']))->format('d/m/Y')?>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="col-xs-12">
        <?=\yii\bootstrap\Tabs::widget([
            'items' => [
                [
                    'label' => \Yii::t('app', 'Overview'),
                    'content' => $this->render('country/overview', [
                        'projectsDataProvider' => $projectsDataProvider,
                        'gradingResponses' => $gradingResponses,
                        'eventsResponses' => $eventsResponses,
                        'healthClustersResponses' => $healthClustersResponses
                    ])
                ],
                [
                    'label' => \Yii::t('app', 'Projects'),
                    'content' => $this->render('country/projects', ['projectsDataProvider' => $projectsDataProvider]),
                    'active' => $layer == 'projects'
                ],
                [
                    'label' => \Yii::t('app', 'Grading'),
                    'content' => $this->render('country/grading', ['gradingResponses' => $gradingResponses]),
                    'visible' => isset($lastGradingResponse),
                    'active' => $layer == 'countryGrades'
                ],
                [
                    'label' => \Yii::t('app', 'Events'),
                    'content' => $this->render('country/events', ['eventsResponses' => $eventsResponses]),
                    'visible' => !empty($eventsResponses),
                    'active' => $layer == 'eventGrades'
                ],
                [
                    'label' => \Yii::t('app', 'Health Clusters'),
                    'content' => $this->render('country/healthClusters', ['healthClustersResponses' => $healthClustersResponses]),
                    'visible' => !empty($healthClustersResponses),
                    'active' => $layer == 'healthClusters'
                ]
            ],
            'options' => [
                'style' => [
                    'margin-bottom' => '10px'
                ]
            ]
        ])?>
    </div>
</div>