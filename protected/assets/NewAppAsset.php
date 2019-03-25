<?php


namespace prime\assets;


use yii\web\AssetBundle;

class NewAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/common.css',
        'css/form.css'
    ];

    public $js = [
    ];

    public $depends = [
        IconBundle::class
    ];
}