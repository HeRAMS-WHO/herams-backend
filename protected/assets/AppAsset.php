<?php

namespace prime\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
    ];

    public $js = [
        '/js/main.js',
        '/js/unobtrusive/tabs.js'
    ];

    public $depends = [
        SassAsset::class,
        AjaxLoadersAsset::class,
        BootBoxAsset::class
    ];
}