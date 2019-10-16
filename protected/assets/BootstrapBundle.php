<?php


namespace prime\assets;


use yii\web\AssetBundle;

class BootstrapBundle extends AssetBundle
{

    public $sourcePath = '@npm/bootstrap/dist';

    public $js = [
        'js/bootstrap.min.js'
    ];

    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-theme.min.css'
    ];
}