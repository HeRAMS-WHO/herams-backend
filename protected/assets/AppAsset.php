<?php

namespace prime\assets;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\AssetManager;
use yii\web\YiiAsset;

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