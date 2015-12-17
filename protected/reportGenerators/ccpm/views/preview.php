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

$scores = [];
foreach($generator->sectionQuestionMapping() as $section => $map) {
    if(count(explode('.', $section)) <= 2) {
        $scores[$section] = $generator->calculateScore($responses, $map, 'average');
    } else {
        $scores[$section] = $generator->calculateScore($responses, $map);
    }
}

$distributions = [];
foreach($generator->sectionQuestionMapping() as $section => $map) {
    if(count(explode('.', $section)) == 3) {
        $distributions[$section] = $generator->calculateDistribution($responses, $map);
    }
}

$this->beginContent('@app/views/layouts/report.php');
?>
<style>
    <?=file_get_contents(__DIR__ . '/../../base/assets/css/grid.css')?>
    <?php include __DIR__ . '/../../base/assets/css/style.php'; ?>
    .background-good, .background-satisfactory, .background-unsatisfactory, .background-weak {
        font-weight: 600;
    }

    .background-good {
        background-color: #1fc63c;
        color: white;
    }

    .background-satisfactory {
        background-color: #ffe003;
        color: white;
    }

    .background-unsatisfactory {
        background-color: #ff9400;
        color: white;
    }

    .background-weak {
        background-color: red;
        color: white;
    }

    .text-good {
        color: #1fc63c;
    }

    .text-satisfactory {
        color: #ffe003;
    }

    .text-unsatisfactory {
        color: #ff9400;
    }

    .text-weak {
        color: red;
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
        <h2><?=\Yii::t('ccpm', 'Overall response rate')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the number of organizations that are part of the cluster')?></span></h2>
        </div>
    </div>
    <?php
    $responseRates = $generator->getResponseRates($responses);
    ?>
    <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates['total']['total1'], 'part' => $responseRates['total']['responses'], 'view' => $this])?>
    <?php
    $graphWidth = 3;
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[1]['total1'], 'part' => $responseRates[1]['responses'], 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[2]['total1'], 'part' => $responseRates[2]['responses'], 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[3]['total1'], 'part' => $responseRates[3]['responses'], 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[4]['total1'], 'part' => $responseRates[4]['responses'], 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[5]['total1'], 'part' => $responseRates[5]['responses'], 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[6]['total1'], 'part' => $responseRates[6]['responses'], 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth, 'view' => $this]),
        ],
        'columnsInRow' => 2
    ]);

    ?>
</div>

    <div class="container-fluid">
        <?=$this->render('header', ['project' => $project])?>
        <div class="row">
            <div class="col-xs-12">
                <h2><?=\Yii::t('ccpm', 'Overall response rate 2')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the number of organizations that are part of the cluster')?></span></h2>
            </div>
        </div>
        <?php
        $responseRates = $generator->getResponseRates($responses);
        ?>
        <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates['total']['total2'], 'part' => $responseRates['total']['responses'], 'view' => $this])?>
        <?php
        $graphWidth = 3;
        echo \prime\widgets\report\Columns::widget([
            'items' => [
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[1]['total2'], 'part' => $responseRates[1]['responses'], 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[2]['total2'], 'part' => $responseRates[2]['responses'], 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[3]['total2'], 'part' => $responseRates[3]['responses'], 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[4]['total2'], 'part' => $responseRates[4]['responses'], 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[5]['total2'], 'part' => $responseRates[5]['responses'], 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[6]['total2'], 'part' => $responseRates[6]['responses'], 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth, 'view' => $this]),
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
                'content' => $this->render('performanceStatusTable', ['generator' => $generator, 'scores' => $scores]),
                'width' => 8
            ],
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<?=$this->render('functionsAndReview', ['generator' => $generator, 'scores' => $scores, 'project' => $project, 'userData' => $userData])?>
<?=$this->render('distributions', ['generator' => $generator, 'distributions' => $distributions, 'project' => $project, 'userData' => $userData], $this)?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Comments')?></h2>
    </div>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'General') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q014']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Supporting service delivery') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q124'], $generator->PPASurveyId => ['q124']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q232'], $generator->PPASurveyId => ['q232']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Planning and strategy development') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q335'], $generator->PPASurveyId => ['q334']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Advocacy') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q422'], $generator->PPASurveyId => ['q422']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q57'], $generator->PPASurveyId => ['q54']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Preparedness for recurrent disasters') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q66'], $generator->PPASurveyId => ['q63']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Accountability to affected populations') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q73'], $generator->PPASurveyId => ['q73']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Others') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q81'], $generator->PPASurveyId => ['q81']], function($value){return !empty($value);})
        ]
    ])?>
</div>
<?php $this->endContent(); ?>