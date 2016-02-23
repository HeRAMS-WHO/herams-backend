<?php

namespace prime\assets;

use yii\web\AssetBundle;

/**
 * Class ReportResizeAsset
 * @package prime\assets
 */
class ReportResizeAsset extends AssetBundle
{
    public $depends = [
        ResizeAsset::class
    ];

    public $js = [
        '/js/iframeResize.js',
    ];
}