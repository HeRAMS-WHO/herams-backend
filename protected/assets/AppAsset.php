<?php

namespace prime\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/main.css',
    ];

    public $js = [
        '/js/main.js',
        [
            '/js/MainModule.js',
            'type' => 'module',
        ],
    ];

    public $depends = [
        NormalizeCssBundle::class,
        BootstrapAsset::class,
        YiiAsset::class,
        SourceSansProBundle::class,
    ];
}
