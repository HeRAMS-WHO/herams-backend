<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
use app\components\Form;

/**
 * @var \prime\models\ar\UserData $userData
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\cd\Generator $generator
 * @var \prime\interfaces\ProjectInterface $project
 * @var \prime\interfaces\SignatureInterface $signature
 * @var \prime\interfaces\ResponseCollectionInterface $responses
 */

$generator = $this->context;

$surveyId = 37964;

/** @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $response */

$this->beginContent('@app/views/layouts/report.php');
?>
<style>
    <?=file_get_contents(__DIR__ . '/../../base/assets/css/grid.css')?>
    <?php include __DIR__ . '/../../base/assets/css/style.php'; ?>
    .spacer {
        height: 50px;
    }

    .spacer-small {
        height: 8px;
    }

    .block-widget div {
        white-space: pre-wrap;
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
            \Yii::t('cd', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('cd', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format($generator->dateFormat)]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <hr>
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('cd', 'Final report')?></h1>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Establishment of the Cluster')?></h2>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'columns' => [
                    'items' => [
                        [
                            'content' => \prime\widgets\report\Block::widget([
                                'items' => [
                                    \Yii::t('cd', 'Cluster formally activated'),
                                    '<span class="unknown">' . \Yii::t('cd', 'Formally activated') . '</span>'
                                ]
                            ]),
                            'width' => 3
                        ],
                        [
                            'content' => \prime\widgets\report\Block::widget([
                                'items' => [
                                    \Yii::t('cd', 'Date of activation'),
                                    \Carbon\Carbon::createFromTimestamp(strtotime($generator->getQuestionValue('q01')))->format($generator->dateFormat)
                                ]
                            ]),
                            'width' => 3
                        ]
                    ]
                ],
                'width' => 6
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Cluster integrated into national coordination structure'),
                        $generator->mapYesNo($generator->getQuestionValue('q11'))
                    ]
                ]),
                'width' => 6
            ],
        ],
        'columnsInRow' => 12
    ]);
    echo Html::tag('div', '', ['class' => 'spacer']);
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Organisation appointed to lead the cluster'),
                        $generator->getQuestionValue('q14')
                    ]
                ]),
                'width' => 6
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Organisation appointed to co-lead the cluster'),
                        $generator->getQuestionValue('q16')
                    ]
                ]),
                'width' => 6
            ],
        ],
        'columnsInRow' => 12
    ]);
    echo Html::tag('div', '', ['class' => 'spacer']);
    ?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Working arrangement for cluster coordinators')?></h2>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Number of Coordinators'),
                        Html::tag('span', $generator->getQuestionValue('q21'), ['class' => 'text-large'])
                    ]
                ]),
                'width' => 6
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Coordinator'),
                        Html::tag('i', \Yii::t('cd', 'Employed by')) . ':<br>' .
                        $generator->getQuestionValue('q22') . '<div class="spacer-small"></div>' .
                        Html::tag('i', \Yii::t('cd', 'Working modalities')) . ':<br>' .
                        $generator->mapWorkingModalities($generator->getQuestionValue('q23')) . '<div class="spacer-small"></div>' .
                        Html::tag('i', \Yii::t('cd', 'Other duties')) . ':<br>' .
                        $generator->getQuestionValue('q24') . '<div class="spacer-small"></div>' .
                        Html::tag('i', \Yii::t('cd', 'Attended training')) . ':<br>' .
                        $generator->mapYesNo($generator->getQuestionValue('q25')) . '<div class="spacer-small"></div>'
                    ]
                ]),
                'width' => 6
            ]
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<div class="container-fluid">
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Working arrangement for staff supporting the coordination')?></h2>
    </div>
    <?php
    $count = (int) $generator->getQuestionValue('q31');
    $contentRight = '';
    $i = 0;
    while ($i < $count - 1) {
        if($i % 2 == 0) {
            if($i > 0) {
                $contentRight .= '</div>';
            }
            $contentRight .= '<div class="row">';
        }

        $contentRight .= '<div class="col-xs-6">' .
            \prime\widgets\report\Block::widget([
                'items' => [
                    \Yii::t('cd', 'Support Staff {0}', [$i + 1]),
                    Html::tag('i', \Yii::t('cd', 'Employed by')) . ':<br>' .
                    $generator->getQuestionValue('q' . (310 + $i * 3 + 1)) . '<div class="spacer-small"></div>' .
                    Html::tag('i', \Yii::t('cd', 'Working modalities')) . ':<br>' .
                    $generator->getQuestionValue('q' . (310 + $i * 3 + 2)) . '<div class="spacer-small"></div>' .
                    Html::tag('i', \Yii::t('cd', 'Support provided')) . ':<br>' .
                    $generator->mapWorkingModalities($generator->getQuestionValue('q' . (310 + $i * 3 + 3))) . '<div class="spacer-small"></div>'
                ]
            ]) . '</div>';
        $i++;
    }
    $contentRight .= '</div>';


    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Number of Coordinators'),
                        Html::tag('span', $generator->getQuestionValue('q31') - 1, ['class' => 'text-large'])
                    ]
                ]),
                'width' => 4
            ],
            [
                'content' => $contentRight,
                'width' => 8
            ]
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', '')?></h2>
    </div>
</div>

<?php $this->endContent(); ?>