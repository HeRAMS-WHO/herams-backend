<?php

namespace prime\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/main.css'
    ];

    public $js = [
        '/js/main.js'
    ];

    public $depends = [
        BootstrapBundle::class,
        YiiAsset::class,
        SourceSansProBundle::class
    ];
}
