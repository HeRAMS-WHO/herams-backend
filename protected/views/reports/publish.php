<?php

use app\components\Html;

/**
 * @var string $renderUrl
 * @var \yii\web\View $this
 * @var string $reportGenerator
 * @var int $projectId
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'Preview'),
        'url' => [
            'reports/preview',
            'reportGenerator' => $reportGenerator,
            'projectId' => $projectId
        ]
    ],
    [
        'label' => \Yii::t('app', 'Publish'),
        'url' => [
            'reports/publish',
            'reportGenerator' => $reportGenerator,
            'projectId' => $projectId
        ],
        'linkOptions' => [
            'data-method' => 'post',
            'data-confirm' => \Yii::t('app', 'Are you sure you want to publish this report and save it to the marketplace?')
        ]
    ]
];

echo Html::tag('iframe', '', ['src' => $finalUrl, 'style' => ['width' => '100%', 'height' => '500px']]);
