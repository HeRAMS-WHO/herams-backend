<?php


namespace prime\assets;


use yii\web\AssetBundle;

class DashboardBundle extends AssetBundle
{

    public $baseUrl = '/css';
    public $css = [
        'dashboard.css',
        'form.css'
    ];

    public $depends = [
        ToastBundle::class,
        IconBundle::class,
        SourceSansProBundle::class
    ];
}