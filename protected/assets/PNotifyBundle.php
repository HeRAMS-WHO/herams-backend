<?php


namespace prime\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class PNotifyBundle extends AssetBundle
{
    public $sourcePath = '@npm/pnotify';
    public $css = [
        'dist/pnotify.css',
        'dist/pnotify.buttons.css',
        'dist/pnotify.brighttheme.css',
        'dist/pnotify.buttons.css'
    ];

    public $depends = [
        JqueryAsset::class
    ];

    public $js = [
        'dist/pnotify.js',
//        'dist/pnotify.buttons.js',
//        'dist/pnotify.animate.js',
//        'dist/pnotify.confirm.js'
    ];
}