<?php


namespace app\assets;


use yii\web\AssetBundle;

class FontBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/fonts';

    public $css = [
        'fonts.css'
    ];
}