<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var array $scores
 */

?>
<style>
    .pst {
        border-collapse: collapse;
        width: 100%;
        font-size: 1em;
    }

    .pst td {
        vertical-align: top;
        padding: 10px;
    }

    .pst td > span {
        display: block;
        width: 100%;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .pst .pst-header > td{
        border-bottom: 1px solid;
        padding-bottom: 1px;
        vertical-align: bottom;
    }

    .pst tr td:first-child {
        width: 6%;
    }

    .pst tr td:nth-child(2) {
        width: 64%;
    }

    .pst tr td:nth-child(3) {
        width: 30%;
        text-align: center;
    }


</style>
<?php
    echo \prime\widgets\report\Table::widget([
        'rows' => [
            [
                'cells' => [
                    '1',
                    \Yii::t('ccpm', 'Supporting service delivery'),
                    ''
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
            [
                'cells' => [
                    '1.1',
                    \Yii::t('ccpm', 'Provide a platform to ensure that service delivery is driven by the agreed strategic priorities'),
                    "<span class='background-{$generator->mapStatus($scores['1.1'])}'>" . ucfirst($generator->mapStatus($scores['1.1'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '1.2',
                    \Yii::t('ccpm', 'Developing mechanisms that eliminate duplication of service delivery'),
                    "<span class='background-{$generator->mapStatus($scores['1.2'])}'>" . ucfirst($generator->mapStatus($scores['1.2'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '2',
                    \Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team'),
                    ''
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
            [
                'cells' => [
                    '2.1',
                    \Yii::t('ccpm', 'Needs assessment and gap analysis'),
                    "<span class='background-{$generator->mapStatus($scores['2.1'])}'>" . ucfirst($generator->mapStatus($scores['2.1'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '2.2',
                    \Yii::t('ccpm', 'Analysis to identify and address (emerging) gaps, obstacles, duplication, and cross-cutting issues'),
                    "<span class='background-{$generator->mapStatus($scores['2.2'])}'>" . ucfirst($generator->mapStatus($scores['2.2'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '2.3',
                    \Yii::t('ccpm', 'Prioritizing on the basis of response analysis'),
                    "<span class='background-{$generator->mapStatus($scores['2.3'])}'>" . ucfirst($generator->mapStatus($scores['2.3'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '3',
                    \Yii::t('ccpm', 'Planning and strategy development'),
                    ''
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
            [
                'cells' => [
                    '3.1',
                    \Yii::t('ccpm', 'Developing sectoral plans, objectives and indicators that directly support HC/HCT strategic priorities'),
                    "<span class='background-{$generator->mapStatus($scores['3.1'])}'>" . ucfirst($generator->mapStatus($scores['3.1'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '3.2',
                    \Yii::t('ccpm', 'Adherence to and application of standards and guidelines'),
                    "<span class='background-{$generator->mapStatus($scores['3.2'])}'>" . ucfirst($generator->mapStatus($scores['3.2'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '3.3',
                    \Yii::t('ccpm', 'Clarifying funding needs, prioritization, and cluster contributions to HC funding needs'),
                    "<span class='background-{$generator->mapStatus($scores['3.3'])}'>" . ucfirst($generator->mapStatus($scores['3.3'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '4',
                    \Yii::t('ccpm', 'Advocacy'),
                    ''
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
            [
                'cells' => [
                    '4.1',
                    \Yii::t('ccpm', 'Identifying advocacy concerns that contribute to HC and HCT messaging and action'),
                    "<span class='background-{$generator->mapStatus($scores['4.1'])}'>" . ucfirst($generator->mapStatus($scores['4.1'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '4.2',
                    \Yii::t('ccpm', 'Undertaking advocacy activities on behalf of cluster participants and affected people'),
                    "<span class='background-{$generator->mapStatus($scores['4.2'])}'>" . ucfirst($generator->mapStatus($scores['4.2'])) . "</span>"
                ]
            ],
            [
                'cells' => [
                    '5',
                    \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results'),
                    "<span class='background-{$generator->mapStatus($scores['5'])}'>" . ucfirst($generator->mapStatus($scores['5'])) . "</span>"
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
            [
                'cells' => [
                    '6',
                    \Yii::t('ccpm', 'Preparedness for recurrent disasters'),
                    "<span class='background-{$generator->mapStatus($scores['6'])}'>" . ucfirst($generator->mapStatus($scores['6'])) . "</span>"
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
            [
                'cells' => [
                    '7',
                    \Yii::t('ccpm', 'Accountability to affected populations'),
                    "<span class='background-{$generator->mapStatus($scores['7'])}'>" . ucfirst($generator->mapStatus($scores['7'])) . "</span>"
                ],
                'options' => [
                    'class' => ['pst-header']
                ]
            ],
        ],
        'options' => [
            'style' => [
                'border-style' => 'collapse'
            ],
            'class' => [
                'pst'
            ]
        ]
    ]);
