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
// Dynamically resize iframe.
$this->registerAssetBundle(\prime\assets\ResizeAsset::class);
$this->registerJs('
        var $iframe = $("iframe");
        var resizer = function(e) {
            console.log("resizer");
            console.log(e);
            $iframe.height($iframe.contents().find("body").height());
            $iframe.width($iframe.contents().find("body").width());
        };

        $iframe.on("load", function() {
            var $body = $iframe.contents().find("body");
            $body.on("mresize", resizer);
            console.log($body);
            $body.trigger("mresize");
        });

    ', $this::POS_READY);
echo Html::tag('div', '', ['id' => 'response']);
echo Html::tag('iframe', '', ['src' => $previewUrl, 'style' => [], 'id' => 'preview']);
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
