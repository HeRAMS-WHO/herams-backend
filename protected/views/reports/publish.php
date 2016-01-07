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
echo Html::tag('iframe', '', ['src' => $finalUrl, 'style' => ['width' => '100%', 'height' => '500px']]);
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