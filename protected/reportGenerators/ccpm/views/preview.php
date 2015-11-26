<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
use app\components\Form;

/**
 * @var \prime\models\ar\UserData $userData
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var \prime\interfaces\ProjectInterface $project
 * @var \prime\interfaces\SignatureInterface $signature
 */

$generator = $this->context;

?>
<style>
    <?=file_get_contents(__DIR__ . '/../assets/css/grid.css')?>
    @font-face {
        font-family: "Open Sans";
        src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(\yii\helpers\Url::to('@app/assets/fonts/OpenSans-Regular.ttf')))?>) format("truetype");
        font-style: normal;
        font-weight: 400;
    }

    @font-face {
        font-family: "Open Sans";
        src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(\yii\helpers\Url::to('@app/assets/fonts/OpenSans-Semibold.ttf')))?>) format("truetype");
        font-style: normal;
        font-weight: 600;
    }

    @font-face {
        font-family: "Open Sans";
        src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(\yii\helpers\Url::to('@app/assets/fonts/OpenSans-Bold.ttf')))?>) format("truetype");
        font-style: normal;
        font-weight: 900;
    }

    body {
        font-family: "Open Sans";
        color: #666;
    }

    .text-large {
        font-size: 2.5em;
    }

    .text-medium {
        font-size: 1.5em;
    }

    .background-good {
        background-color: green;
        color: white;
    }

    .text-good {
        color: green;
    }

    .text-satisfactory {
        color: yellow;
    }

    .text-unsatisfactory {
        color: orange;
    }

    .text-weak {
        color: red;
    }

    h1 {
        font-size: 3em;
        font-weight: 400;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    h2 {
        font-size: 2em;
        font-weight: 400;
    }

    h4 {
        margin-top: 0px;
        margin-bottom: 5px;
        font-weight: 400;
        font-size: 1.1em;
    }

    table {
        font-size: 1em;
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #e8e8e8;
    }

    td {
        padding: 5px;
    }

    hr {
        margin-top: 10px;
        margin-bottom: 10px;
        border-color: #8d8d8d;
        border-width: 2px;
    }

    @media print {
        .container-fluid {page-break-after: always;}

        body {
            font-size: 1.1em;
        }
    }

    .container-fluid:before {
        content: ;
    }

</style>

<div class="container-fluid">
    <?=$generator->renderHeader()?>
    <div class="row">
        <h1 class="col-xs-12"><?=$project->getLocality()?></h1>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \Yii::t('ccpm', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('ccpm', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format('d F - Y')]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <hr>
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('ccpm', 'Final report')?></h1>
    </div>
</div>

<div class="container-fluid">
    <?=$generator->renderHeader()?>
    <div class="row">
        <div class="col-xs-12">
        <h2><?=\Yii::t('ccpm', 'Overall response rate')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the number of organizations tat are part of the cluster')?></span></h2>
        </div>
    </div>
    <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => 72, 'part' => 20])?>
    <?php
    $graphWidth = 3;
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \prime\widgets\report\GraphWithNumbers::widget(['total' => 27, 'part' => 6, 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => 30, 'part' => 11, 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => 6, 'part' => 1, 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => 2, 'part' => 1, 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => 6, 'part' => 1, 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => 1, 'part' => 0, 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth]),
        ],
        'columnsInRow' => 2
    ]);

    ?>
</div>

<div class="container-fluid">
    <?=$generator->renderHeader()?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Overall Performance')?></h2>
    </div>
    <?php

    $performanceStatusBlockColumns = [
        'items' => [
            [
                'content' => \Yii::t('ccpm', 'Score') . '<hr>> 75 %<br>51 % - 75 %<br>26 % - 50 %<br>< 26 %',
                'width' => 6
            ],
            [
                'content' => \Yii::t('Performance status') . '<hr><span class="text-good">Good</span><br><span class="text-satisfactory">Satisfactory</span><br><span class="text-unsatisfactory">Unsatisfactory</span><br><span class="text-weak">Weak</span>',
                'width' => 6
            ]
        ],
        'columnsInRow' => 12
    ];

    $performanceStatusBlock =
        '<div class="col-xs-12" style="border: 1px solid black; padding-top: 15px; padding-bottom: 15px;">' . \prime\widgets\report\Columns::widget($performanceStatusBlockColumns) . '</div>';

    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => $performanceStatusBlock,
                'width' => 4
            ],
            [
                'content' => $this->render('performanceStatusTable'),
                'width' => 8
            ],
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<div class="container-fluid">
    <?=$generator->renderHeader()?>

    <!--BEGIN Only on first page of function...-->
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Performance per function and review')?></h2>
    </div>
    <!--END Only on first page of function...-->

    <div class="row">
        <h3 class="col-xs-12">1. Supporting service delivery</h3>
    </div>
    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '1.1.',
        'score' => 'good',
        'title' => 'Provide a platform to ensure that service delivery is driven by the agreed strategic priorities',
        'scores' => [
            'List of partners regularly updated' => '100%',
            'Support/engagement of cluster with national coordination mechanisms' => '75%',
            'Regular cluster meetings organised' => '100%',
            'Attendance of cluster partners to cluster meetings' => '100%',
            'Level of decision making power of staff attending cluster meetings' => '100%',
            'Conditions for optimal participation of national and international stakeholders' => '75%',
        ],
        'notes' => [
            'Indicative characteristics of functions' => 'Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.',
            'Constraints, unexpected circumstances and/or success factors and/or good practice identified' => '',
            'Follow-up actions, with timeline and/or support required (when status is orange or red)' => ''
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$generator->renderHeader()?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Comments')?></h2>
    </div>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            'General' => [
                'pour la Croix rouge FranÃ§aise, membre du Cluster santÃ©',
                'RAS',
                'Nous avons constatÃ© que les projets CHF soumis par les ONG nationales n\'attirent pas l\'attention des diffÃ©rentes coordinations de clusters .Nous'
            ]
        ]
    ])?>
</div>