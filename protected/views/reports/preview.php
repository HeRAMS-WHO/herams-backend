<?php

use app\components\Html;

/**
 * @var string $previewUrl
 * @var \yii\web\View $this
 * @var string $reportGenerator
 * @var \prime\models\ar\Project $project
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'Save'),
        'url' => '#',
        'linkOptions' => [
            'id' => 'save_preview',
            'data' => [
                'loader' => 'overlay-window',
                'loader-text' => \Yii::t('app', 'Saving')
            ]
        ]
    ],
    [
        'label' => \Yii::t('app', 'Publish'),
        'url' => [
            'reports/publish',
            'reportGenerator' => $reportGenerator,
            'projectId' => $project->id
        ],
        'linkOptions' => [
            'id' => 'publish_preview',
            'data' => [
                'loader' => 'overlay-window',
                'loader-text' => \Yii::t('app', 'Generating')
            ]
        ]
    ]
];

$this->registerJsFile('@web/js/preview.js', ['depends' => \yii\web\JqueryAsset::class]);

echo Html::tag('div', '', ['id' => 'response']);
echo Html::tag('iframe', '', ['src' => $previewUrl, 'style' => ['width' => '100%', 'height' => '500px'], 'id' => 'preview']);
