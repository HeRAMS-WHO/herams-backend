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
 */

$generator = $this->context;

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
        word-wrap: break-word;
    }

    .blocks-same-title-height-1 .block-widget > div:first-child{
        height: 20px;
    }

    .blocks-same-title-height-2 .block-widget > div:first-child{
        height: 40px;
    }

    .blocks-same-title-height-3 .block-widget > div:first-child{
        height: 60px;
    }

    .block-widget .row:before {
        content: "";
    }

    h4 {
        margin-top: 5px;
        margin-bottom: 8px;
        font-size: 1.1em;
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
            '<br>' . \Yii::t('cd', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('cd', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format($generator->dateFormat)]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <br>
    <hr>
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('cd', 'Final report')?></h1>
    </div>
    <div class="row">
        <div class="col-xs-12" style="text-align: right;"><img style="height: 80px;" src="data:image/gif;base64,<?=base64_encode(file_get_contents(__DIR__ . '/../../base/assets/img/who-logo.png'))?>" /></div>
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
                                    ($generator->getQuestionValue('q11') == '1') ? \Yii::t('cd', 'Not formally activated') : ($generator->getQuestionValue('q11') == '2' ? \Yii::t('cd', 'Formally activated') : \Yii::t('cd', 'Do not know'))
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
                        $generator->mapYesNo($generator->getQuestionValue('q13'))
                    ]
                ]),
                'width' => 6
            ],
        ],
        'columnsInRow' => 12,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-2']
        ]
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
        'columnsInRow' => 12,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-1']
        ]
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
                'columns' => [
                    'items' => [
                        [
                            'content' => \prime\widgets\report\Block::widget([
                                'items' => [
                                    \Yii::t('cd', 'Coordinator {0}', [$generator->getQuestionValue('q21') == 2 ? '1' : '']),
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
                        ],
                        [
                            'content' => ($generator->getQuestionValue('q21') == 2) ?
                                \prime\widgets\report\Block::widget([
                                    'items' => [
                                        \Yii::t('cd', 'Coordinator 2'),
                                        Html::tag('i', \Yii::t('cd', 'Employed by')) . ':<br>' .
                                        $generator->getQuestionValue('q26') . '<div class="spacer-small"></div>' .
                                        Html::tag('i', \Yii::t('cd', 'Working modalities')) . ':<br>' .
                                        $generator->mapWorkingModalities($generator->getQuestionValue('q27')) . '<div class="spacer-small"></div>' .
                                        Html::tag('i', \Yii::t('cd', 'Other duties')) . ':<br>' .
                                        $generator->getQuestionValue('q28') . '<div class="spacer-small"></div>' .
                                        Html::tag('i', \Yii::t('cd', 'Attended training')) . ':<br>' .
                                        $generator->mapYesNo($generator->getQuestionValue('q29')) . '<div class="spacer-small"></div>'
                                    ]
                                ]) : ''
                            ,
                            'width' => 6
                        ],
                    ]
                ],
                'width' => 6
            ]
        ],
        'columnsInRow' => 12,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-1']
        ]
    ]);
    ?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
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
                    $generator->mapWorkingModalities($generator->getQuestionValue('q' . (310 + $i * 3 + 3))) . '<div class="spacer-small"></div>' .
                    Html::tag('i', \Yii::t('cd', 'Support provided')) . ':<br>' .
                    $generator->getQuestionValue('q' . (310 + $i * 3 + 2)) . '<div class="spacer-small"></div>'
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
                        \Yii::t('cd', 'Number of additional support staff'),
                        Html::tag('span', $generator->getQuestionValue('q31') - 1, ['class' => 'text-large'])
                    ]
                ]),
                'width' => 4
            ],
            [
                'content' => $contentRight,
                'width' => 8,

            ]
        ],
        'columnsInRow' => 12,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-2']
        ]
    ]);
    ?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Strategic Advisory and Technical Working Groups')?></h2>
    </div>
    <div class="row">
        <h3 class="col-xs-12"><?=\Yii::t('cd', 'Strategic Advisory Group')?></h3>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Exists'),
                        $generator->mapYesNo($generator->getQuestionValue('q326'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Topics'),
                        $generator->getQuestionValue('q327')
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Organisations belonging to this group'),
                        $generator->getQuestionValue('q328')
                    ]
                ])
            ]
        ],
        'columnsInRow' => 3,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-2']
        ]
    ]);
    ?>
    <div class="spacer"></div>
    <div class="row">
        <h3 class="col-xs-12"><?=\Yii::t('cd', 'Technical Working Groups')?></h3>
    </div>
    <?php
    for ($i = 1; $i <= $generator->getQuestionValue('q329'); $i++) {
        echo \prime\widgets\report\Columns::widget([
            'items' => [
                [
                    'content' => \prime\widgets\report\Block::widget([
                        'items' => [
                            \Yii::t('cd', 'Name'),
                            $generator->getQuestionValue('q' . (329 + ($i - 1) * 3 + 1))
                        ]
                    ])
                ],
                [
                    'content' => \prime\widgets\report\Block::widget([
                        'items' => [
                            \Yii::t('cd', 'Topics'),
                            $generator->getQuestionValue('q' . (329 + ($i - 1) * 3 + 2))
                        ]
                    ])
                ],
                [
                    'content' => \prime\widgets\report\Block::widget([
                        'items' => [
                            \Yii::t('cd', 'Organisations belonging to this group'),
                            $generator->getQuestionValue('q' . (329 + ($i - 1) * 3 + 3))
                        ]
                    ])
                ]
            ],
            'columnsInRow' => 3,
            'rowOptions' => [
                'class' => ['blocks-same-title-height-2']
            ]
        ]);
        echo ' <div class="spacer-small"></div>';
    }
    ?>
    <div class="spacer"></div>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Participation in the cluster of identified focal points for cross-cutting issues')?></h2>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Age'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[1]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Gender'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[2]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Diversity, <small>other than age and gender</small>'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[3]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Human Rights'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[4]'))
                    ]
                ])
            ],
        ],
        'columnsInRow' => 4,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-2']
        ]
    ]);

    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Protection, <small>incl. sexual and gender based violence</small>'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[5]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Environment'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[6]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'HIV/AIDS'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[7]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Disability'),
                        $generator->mapYesNo($generator->getQuestionValue('q345[8]'))
                    ]
                ])
            ],
        ],
        'columnsInRow' => 4,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-2']
        ]
    ]);
    ?>
    <div class="spacer"></div>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Organisations chairing or co-chairing the meetings')?></h2>
        <div class="col-xs-12">
            <?=$generator->getQuestionValue('q346[1]') == 'Y' ? \Yii::t('cd', 'Cluster lead organization') . '<br>' : ''?>
            <?=$generator->getQuestionValue('q346[2]') == 'Y' ? \Yii::t('cd', 'Co-lead organization') . '<br>' : ''?>
            <?=$generator->getQuestionValue('q346[3]') == 'Y' ? \Yii::t('cd', 'Government') . '<br>' : ''?>
            <?=$generator->getQuestionValue('q346[4]') == 'Y' ? \Yii::t('cd', 'Other partners') . '<br>' : ''?>
            <?=$generator->getQuestionValue('q346[5]') == 'Y' ? \Yii::t('cd', 'Do not know') . '<br>' : ''?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Number of cluster participants')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'including observers')?>)</span></h2>
    </div>

    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'International NGOs'),
                        (string) round($generator->getQuestionValue('q41[1]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'National NGOs'),
                        (string) round($generator->getQuestionValue('q41[2]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'UN Agencies'),
                        (string) round($generator->getQuestionValue('q41[3]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'National Authorities'),
                        (string) round($generator->getQuestionValue('q41[4]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Donors'),
                        (string) round($generator->getQuestionValue('q41[5]'))
                    ]
                ])
            ],
            [
                'content' => \prime\widgets\report\Block::widget([
                    'items' => [
                        \Yii::t('cd', 'Other'),
                        (string) round($generator->getQuestionValue('q41[6]'))
                    ]
                ])
            ],
        ],
        'columnsInRow' => 6,
        'rowOptions' => [
            'class' => ['blocks-same-title-height-2']
        ]
    ]);
    ?>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Organisations participating in the cluster')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'observers identified by an *')?>)</span></h2>
    </div>
    <div class="row">
        <div class="col-xs-12" style="white-space: pre-wrap"><?=$generator->getQuestionValue('q42')?></div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Deliverables')?></h2>
        <h3 class="col-xs-12">1. <?=\Yii::t('cd', 'Supporting service delivery')?></h3>
        <h4 class="col-xs-12">1.1. <?=\Yii::t('cd', 'Provide a platform to ensure that service delivery is driven by the agreed strategic priorities')?></h4>
        <div class="col-xs-12">
        <?php
        echo \prime\widgets\report\Columns::widget([
            'items' => [
                [
                    'content' => \prime\widgets\report\DeliverableBlock::widget([
                        'title' => \Yii::t('cd', 'Up-to-date lists of partners'),
                        'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[1]')),
                        'link' => $generator->getQuestionValue('q51[1comment]')
                    ])
                ],
                [
                    'content' => \prime\widgets\report\DeliverableBlock::widget([
                        'title' => \Yii::t('cd', 'Meeting minutes'),
                        'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[2]')),
                        'link' => $generator->getQuestionValue('q51[2comment]')
                    ])
                ]
            ],
            'columnsInRow' => 2,
            'rowOptions' => [
                'class' => ['blocks-same-title-height-1']
            ]
        ]);
        ?>
        </div>
        <h4 class="col-xs-12">1.2. <?=\Yii::t('cd', 'Develop mechanisms to eliminate duplication of service delivery')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Mapping of partner geographic presence and programme activities (e.g.3W)'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[3]')),
                            'link' => $generator->getQuestionValue('q51[3comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Analysis of gaps and overlaps derived from the mapping of partner geographic presence and programme activities'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[4]')),
                            'link' => $generator->getQuestionValue('q51[4comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
                'rowOptions' => [
                    'class' => ['blocks-same-title-height-3']
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="spacer"></div>
    <div class="row">
        <h3 class="col-xs-12">2. <?=\Yii::t('cd', 'Informing strategic decision-making of the HC/HCT for the humanitarian response')?></h3>
        <h4 class="col-xs-12">2.1. <?=\Yii::t('cd', 'Needs assessment and gap analysis (across other sectors and within the sector)')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Needs assessment tools and guidance'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[6]')),
                            'link' => $generator->getQuestionValue('q51[6comment]')
                        ])
                    ],
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
        <h4 class="col-xs-12">2.2. <?=\Yii::t('cd', 'Analysis to identify and address (emerging) gaps, obstacles, duplication, and cross-cutting issues')?></h4>
        <h4 class="col-xs-12">2.3. <?=\Yii::t('cd', 'Prioritization, grounded in response analysis')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Joint sectoral analyses of situations'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[7]')),
                            'link' => $generator->getQuestionValue('q51[7comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Inter-cluster strategic intervention matrices'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[5]')),
                            'link' => $generator->getQuestionValue('q51[5comment]')
                        ])
                    ],
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h3 class="col-xs-12">3. <?=\Yii::t('cd', 'Planning and strategy development')?></h3>
        <h4 class="col-xs-12">3.1. <?=\Yii::t('cd', 'Develop sectoral plans, objectives and indicators directly supporting realization of the HC/HCT strategic priorities')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Cluster Strategic plan'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[8]')),
                            'link' => $generator->getQuestionValue('q51[8comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Cluster deactivation criteria and phasing out strategy'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[9]')),
                            'link' => $generator->getQuestionValue('q51[9comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
                'rowOptions' => [
                    'class' => ['blocks-same-title-height-2']
                ]
            ]);
            ?>
        </div>
        <h4 class="col-xs-12">3.2. <?=\Yii::t('cd', 'Application and adherence to existing standards and guidelines')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Technical standards and guidance'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[10]')),
                            'link' => $generator->getQuestionValue('q51[10comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
        <h4 class="col-xs-12">3.3. <?=\Yii::t('cd', 'Clarify funding requirements, prioritization, and cluster contributions to HC’s overall humanitarian funding considerations')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Report on funding status of cluster against needs'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[11]')),
                            'link' => $generator->getQuestionValue('q51[11comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
    </div>
    <div class="spacer"></div>
    <div class="row">
        <h3 class="col-xs-12">4. <?=\Yii::t('cd', 'Planning and strategy development')?></h3>
        <h4 class="col-xs-12">4.1. <?=\Yii::t('cd', 'Identify advocacy concerns to contribute to HC and HCT messaging and action')?></h4>
        <h4 class="col-xs-12">4.2. <?=\Yii::t('cd', 'Undertaking advocacy activities on behalf of cluster participants and the affected population')?></h4>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Press releases on behalf of cluster'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[12]')),
                            'link' => $generator->getQuestionValue('q51[12comment]')
                        ])
                    ],
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h3 class="col-xs-12">5. <?=\Yii::t('cd', 'Monitoring and reporting')?></h3>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Programme monitoring tools with indicators'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[13]')),
                            'link' => $generator->getQuestionValue('q51[13comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Progress/monitoring reports against strategic plan'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[14]')),
                            'link' => $generator->getQuestionValue('q51[14comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Progress/monitoring reports against work plan'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[15]')),
                            'link' => $generator->getQuestionValue('q51[15comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Cluster bulletins'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[16]')),
                            'link' => $generator->getQuestionValue('q51[16comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Sectoral situation reports'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[17]')),
                            'link' => $generator->getQuestionValue('q51[17comment]')
                        ])
                    ],
                ],
                'columnsInRow' => 2,
                'rowOptions' => [
                    'class' => ['blocks-same-title-height-2']
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="spacer"></div>
    <div class="row">
        <h3 class="col-xs-12">6. <?=\Yii::t('cd', 'Contingency planning/preparedness')?></h3>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Risk assessment analysis'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[18]')),
                            'link' => $generator->getQuestionValue('q51[18comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Preparedness Plans'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[19]')),
                            'link' => $generator->getQuestionValue('q51[19comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
    </div>
    <div class="spacer"></div>
    <div class="row">
        <h3 class="col-xs-12">7. <?=\Yii::t('cd', 'Accountability to affected population')?></h3>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Review of cluster accountability to affected population'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[20]')),
                            'link' => $generator->getQuestionValue('q51[20comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Framework of cluster accountability to affected population'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q51[21]')),
                            'link' => $generator->getQuestionValue('q51[21comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('cd', 'Communication')?></h2>
        <div class="col-xs-12">
            <?php
            echo \prime\widgets\report\Columns::widget([
                'items' => [
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Cluster information available on a cluster specific website'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q61[1]')),
                            'link' => $generator->getQuestionValue('q61[1comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Cluster information available on a cluster specific webpage on an inter-agency website'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q61[2]')),
                            'link' => $generator->getQuestionValue('q61[2comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'Other'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q61[3]')),
                            'link' => $generator->getQuestionValue('q61[3comment]')
                        ])
                    ],
                    [
                        'content' => \prime\widgets\report\DeliverableBlock::widget([
                            'title' => \Yii::t('cd', 'None of the above'),
                            'available' =>$generator->mapYesNo($generator->getQuestionValue('q61[4]')),
                            'link' => $generator->getQuestionValue('q61[4comment]')
                        ])
                    ]
                ],
                'columnsInRow' => 2,
            ]);
            ?>
        </div>
    </div>
    <div class="spacer"></div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => \Yii::t('cd', 'Comments'),
            ],
            [
                'content' => Html::tag('span', $generator->getQuestionValue('q71'), ['style' => ['white-space' => 'pre-wrap']]),
                'width' => 5
            ]
        ]
    ]);
    ?>
</div>

<?php $this->endContent(); ?>