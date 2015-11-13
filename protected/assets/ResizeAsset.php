<?php

namespace prime\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class ResizeAsset
 * @package prime\assets
 */
class ResizeAsset extends AssetBundle {

    public $css = [

    ];

    public $depends = [
        JqueryAsset::class
    ];

    public $js = [
        'mresize.js',
    ];

    public $sourcePath = '@bower/mresize';

}