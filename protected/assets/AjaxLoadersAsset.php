<?php

namespace prime\assets;

use yii\web\AssetBundle;

class AjaxLoadersAsset extends AssetBundle
{
    public $css = [
        '/css/unobtrusive/ajaxLoaders.css'
    ];

    public $depends = [
        IsLoadingAsset::class
    ];

    public $js = [
        '/js/unobtrusive/ajaxLoaders.js',
    ];
}