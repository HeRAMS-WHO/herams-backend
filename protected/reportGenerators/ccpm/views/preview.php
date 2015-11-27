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
 * @var \prime\interfaces\ResponseCollectionInterface $responses
 */

$generator = $this->context;

$scores = [
    '1.1' => $generator->calculateScore($responses, [67825 => ['q111', 'q112', 'q114', 'q118', 'q113', 'q115', 'q119', 'q116', 'q117'], 22814 => ['q111', 'q112', 'q113', 'q114', 'q115', 'q116']], 'average'),
    '1.1.1' => $generator->calculateScore($responses, [67825 => ['q111'], 22814 => []]),
    '1.1.2' => $generator->calculateScore($responses, [67825 => ['q112'], 22814 => ['q111']]),
    '1.1.3' => $generator->calculateScore($responses, [67825 => ['q114'], 22814 => ['q112']]),
    '1.1.4' => $generator->calculateScore($responses, [67825 => [], 22814 => ['q113']]),
    '1.1.5' => $generator->calculateScore($responses, [67825 => ['q118'], 22814 => ['q114']]),
    '1.1.6' => $generator->calculateScore($responses, [67825 => ['q113'], 22814 => []]),
    '1.1.7' => $generator->calculateScore($responses, [67825 => ['q115'], 22814 => ['q115']]),
    '1.1.8' => $generator->calculateScore($responses, [67825 => ['q119'], 22814 => ['q116']]),
    '1.1.9' => $generator->calculateScore($responses, [67825 => ['q116'], 22814 => []]),
    '1.1.10' => $generator->calculateScore($responses, [67825 => ['q117'], 22814 => []]),
    '1.2' => $generator->calculateScore($responses, [67825 => ['q121', 'q122', 'q123'], 22814 => ['q121', 'q122', 'q123']], 'average'),
    '1.2.1' => $generator->calculateScore($responses, [67825 => ['q121'], 22814 => []]),
    '1.2.2' => $generator->calculateScore($responses, [67825 => ['q122'], 22814 => ['q121']]),
    '1.2.3' => $generator->calculateScore($responses, [67825 => [], 22814 => ['q122']]),
    '1.2.4' => $generator->calculateScore($responses, [67825 => ['q123'], 22814 => ['q123']]),
    '2.1' => $generator->calculateScore($responses, [67825 => ['q211', 'q212', 'q213'], 22814 => ['q211', 'q212', 'q213']], 'average'),
    '2.1.1' => $generator->calculateScore($responses, [67825 => ['q211'], 22814 => ['q211']]),
    '2.1.2' => $generator->calculateScore($responses, [67825 => ['q212'], 22814 => ['q212']]),
    '2.1.3' => $generator->calculateScore($responses, [67825 => ['q213'], 22814 => ['q213']]),
    '2.2' => $generator->calculateScore($responses, [67825 => ['q221', 'q222[1]', 'q222[2]', 'q222[3]', 'q222[4]', 'q222[5]', 'q223[1]', 'q223[2]', 'q223[3]', 'q223[4]', 'q223[5]', 'q223[6]', 'q223[7]', 'q223[8]'], 22814 => ['q221', 'q222[1]', 'q222[2]', 'q222[3]', 'q222[4]', 'q222[5]', 'q223[1]', 'q223[2]', 'q223[3]', 'q223[4]', 'q223[5]', 'q223[6]', 'q223[7]', 'q223[8]']], 'average'),
    '2.2.1' => $generator->calculateScore($responses, [67825 => ['q221'], 22814 => ['q221']]),
    '2.2.2' => $generator->calculateScore($responses, [67825 => ['q222[1]'], 22814 => ['q222[1]']]),
    '2.2.3' => $generator->calculateScore($responses, [67825 => ['q222[2]'], 22814 => ['q222[2]']]),
    '2.2.4' => $generator->calculateScore($responses, [67825 => ['q222[3]'], 22814 => ['q222[3]']]),
    '2.2.5' => $generator->calculateScore($responses, [67825 => ['q222[4]'], 22814 => ['q222[4]']]),
    '2.2.6' => $generator->calculateScore($responses, [67825 => ['q222[5]'], 22814 => ['q222[5]']]),
    '2.2.7' => $generator->calculateScore($responses, [67825 => ['q223[1]'], 22814 => ['q223[1]']]),
    '2.2.8' => $generator->calculateScore($responses, [67825 => ['q223[2]'], 22814 => ['q223[2]']]),
    '2.2.9' => $generator->calculateScore($responses, [67825 => ['q223[3]'], 22814 => ['q223[3]']]),
    '2.2.10' => $generator->calculateScore($responses, [67825 => ['q223[4]'], 22814 => ['q223[4]']]),
    '2.2.11' => $generator->calculateScore($responses, [67825 => ['q223[5]'], 22814 => ['q223[5]']]),
    '2.2.12' => $generator->calculateScore($responses, [67825 => ['q223[6]'], 22814 => ['q223[6]']]),
    '2.2.13' => $generator->calculateScore($responses, [67825 => ['q223[7]'], 22814 => ['q223[7]']]),
    '2.2.14' => $generator->calculateScore($responses, [67825 => ['q223[8]'], 22814 => ['q223[8]']]),
    '2.3' => $generator->calculateScore($responses, [67825 => ['q231'], 22814 => ['q231']], 'average'),
    '2.3.1' => $generator->calculateScore($responses, [67825 => ['q231'], 22814 => ['q231']]),
    '3.1' => $generator->calculateScore($responses, [67825 => ['q311', 'q314', 'q312', 'q313', 'q315[1]', 'q315[2]', 'q315[3]', 'q315[4]', 'q315[5]', 'q315[6]', 'q315[7]', 'q315[8]', 'q316', 'q317', 'q318'], 22814 => ['q311', 'q312']], 'average'),
    '3.1.1' => $generator->calculateScore($responses, [67825 => ['q311'], 22814 => []]),
    '3.1.2' => $generator->calculateScore($responses, [67825 => ['q314'], 22814 => ['q311']]),
    '3.1.3' => $generator->calculateScore($responses, [67825 => ['q312'], 22814 => []]),
    '3.1.4' => $generator->calculateScore($responses, [67825 => ['q313'], 22814 => []]),
    '3.1.5' => $generator->calculateScore($responses, [67825 => ['q315[1]'], 22814 => []]),
    '3.1.6' => $generator->calculateScore($responses, [67825 => ['q315[2]'], 22814 => []]),
    '3.1.7' => $generator->calculateScore($responses, [67825 => ['q315[3]'], 22814 => []]),
    '3.1.8' => $generator->calculateScore($responses, [67825 => ['q315[4]'], 22814 => []]),
    '3.1.9' => $generator->calculateScore($responses, [67825 => ['q315[5]'], 22814 => []]),
    '3.1.10' => $generator->calculateScore($responses, [67825 => ['q315[6]'], 22814 => []]),
    '3.1.11' => $generator->calculateScore($responses, [67825 => ['q315[7]'], 22814 => []]),
    '3.1.12' => $generator->calculateScore($responses, [67825 => ['q315[8]'], 22814 => []]),
    '3.1.13' => $generator->calculateScore($responses, [67825 => ['q316'], 22814 => []]),
    '3.1.14' => $generator->calculateScore($responses, [67825 => ['q317'], 22814 => ['q312']]),
    '3.1.15' => $generator->calculateScore($responses, [67825 => ['q318'], 22814 => []]),
    '3.2' => $generator->calculateScore($responses, [67825 => ['q321', 'q322'], 22814 => ['q321']], 'average'),
    '3.2.1' => $generator->calculateScore($responses, [67825 => ['q321'], 22814 => []]),
    '3.2.2' => $generator->calculateScore($responses, [67825 => ['q322'], 22814 => ['q321']]),
    '3.3' => $generator->calculateScore($responses, [67825 => ['q331', 'q332', 'q333', 'q334'], 22814 => ['q331', 'q332', 'q333']], 'average'),
    '3.3.1' => $generator->calculateScore($responses, [67825 => ['q331'], 22814 => ['q331']]),
    '3.3.2' => $generator->calculateScore($responses, [67825 => ['q332'], 22814 => ['q332']]),
    '3.3.3' => $generator->calculateScore($responses, [67825 => ['q333'], 22814 => []]),
    '3.3.4' => $generator->calculateScore($responses, [67825 => ['q334'], 22814 => ['q333']]),
    '4.1' => $generator->calculateScore($responses, [67825 => ['q411'], 22814 => ['q411']], 'average'),
    '4.1.1' => $generator->calculateScore($responses, [67825 => ['q411'], 22814 => ['q411']]),
    '4.2' => $generator->calculateScore($responses, [67825 => ['q421'], 22814 => ['q421']], 'average'),
    '4.2.1' => $generator->calculateScore($responses, [67825 => ['q421'], 22814 => ['q421']]),
    '5' => $generator->calculateScore($responses, [67825 => ['q51', 'q52', 'q53', 'q54', 'q55', 'q56'], 22814 => ['q51', 'q52', 'q53']], 'average'),
    '5.1.1' => $generator->calculateScore($responses, [67825 => ['q51'], 22814 => ['q52']]),
    '5.1.2' => $generator->calculateScore($responses, [67825 => ['q52'], 22814 => []]),
    '5.1.3' => $generator->calculateScore($responses, [67825 => ['q53'], 22814 => []]),
    '5.1.4' => $generator->calculateScore($responses, [67825 => ['q54'], 22814 => []]),
    '5.1.5' => $generator->calculateScore($responses, [67825 => ['q55'], 22814 => ['q51']]),
    '5.1.6' => $generator->calculateScore($responses, [67825 => ['q56'], 22814 => ['q53']]),
    '6' => $generator->calculateScore($responses, [67825 => ['q61', 'q62', 'q63', 'q64', 'q65', 'q66'], 22814 => ['q61', 'q62']], 'average'),
    '6.1.1' => $generator->calculateScore($responses, [67825 => ['q61'], 22814 => []]),
    '6.1.2' => $generator->calculateScore($responses, [67825 => ['q62'], 22814 => []]),
    '6.1.3' => $generator->calculateScore($responses, [67825 => ['q63'], 22814 => ['q61']]),
    '6.1.4' => $generator->calculateScore($responses, [67825 => ['q64'], 22814 => ['q62']]),
    '6.1.5' => $generator->calculateScore($responses, [67825 => ['q65'], 22814 => []]),
    '7' => $generator->calculateScore($responses, [67825 => ['q71', 'q72'], 22814 => ['q71', 'q72']], 'average'),
    '7.1.1' => $generator->calculateScore($responses, [67825 => ['q71'], 22814 => ['q71']]),
    '7.1.2' => $generator->calculateScore($responses, [67825 => ['q72'], 22814 => ['q72']]),
];
vdd($scores);

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
    <?=$this->render('header', ['project' => $project])?>
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
    <?=$this->render('header', ['project' => $project])?>
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
    <?=$this->render('header', ['project' => $project])?>
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
                'content' => \Yii::t('ccpm', 'Performance status') . '<hr><span class="text-good">' . \Yii::t('ccpm', 'Good') . '</span><br><span class="text-satisfactory">' . \Yii::t('ccpm', 'Satisfactory') . '</span><br><span class="text-unsatisfactory">' . \Yii::t('ccpm', 'Unsatisfactory') . '</span><br><span class="text-weak">' . \Yii::t('ccpm', 'Weak') . '</span>',
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
                'content' => $this->render('performanceStatusTable', ['generator' => $generator, 'responses' => $responses]),
                'width' => 8
            ],
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

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
    <?=$this->render('header', ['project' => $project])?>
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