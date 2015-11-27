<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var \prime\interfaces\ResponseCollectionInterface $responses
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
                    '1.',
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
                    '<span class="background-">Good</span>'
                ]
            ]
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
