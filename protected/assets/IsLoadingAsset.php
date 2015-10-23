<?php

namespace prime\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class IsLoadingAsset extends AssetBundle
{
    public $css = [

    ];

    public $depends = [
        JqueryAsset::class
    ];

    public $js = [
        'jquery.isloading.js',
    ];

    public $sourcePath = '@bower/is-loading';
}