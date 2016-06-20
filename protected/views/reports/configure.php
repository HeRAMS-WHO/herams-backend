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
    ],
    [
        'label' => \Yii::t('app', 'Print'),
        'linkOptions' => [
            'onclick' => "$('iframe')[0].contentWindow.print();"
        ]
    ]
];

$this->registerJsFile('@web/js/preview.js', ['depends' => \yii\web\JqueryAsset::class]);
// Dynamically resize iframe.
$this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);
echo Html::tag('div', '', ['id' => 'response']);
echo Html::tag('iframe', '', ['src' => $configureUrl, 'style' => [], 'id' => 'preview', 'class' => ['resize']]);
?>
<style>
    body {
        background-color: grey;
    }

    iframe {
        width: 100%;
        height: 500px;
        margin-bottom: 30px;
        border: 0px;
        overflow-y: hidden;
    }
</style>
