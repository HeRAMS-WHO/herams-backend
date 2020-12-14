<?php


namespace prime\assets;

use yii\web\AssetBundle;

class AdminBundle extends AssetBundle
{

    public $baseUrl = '/css';
    public $css = [
        'admin.css',
        'form.css'
    ];

    public $depends = [
        ToastBundle::class,
        IconBundle::class,
        SourceSansProBundle::class,
        MainBundle::class
    ];
}
