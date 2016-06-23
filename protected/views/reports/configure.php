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
        'label' => Html::icon('floppy-disk'),
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Save report configuration'),
        ],
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
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Print report configuration'),
        ],

        'label' => Html::icon(\prime\models\ar\Setting::get('icons.print', 'print')),
        'linkOptions' => [
            'onclick' => "$('iframe')[0].contentWindow.print();"
        ]
    ],

    [
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Proceed to publish'),
        ],

        'label' => Html::icon(\prime\models\ar\Setting::get('icons.proceed', 'arrow-right')),


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
