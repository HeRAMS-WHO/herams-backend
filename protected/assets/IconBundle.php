<?php

namespace prime\assets;

use yii\web\AssetBundle;

class IconBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/icons';

    public $js = [
        'svgxuse.js'
    ];

    public $css = [
        'style.css',
        'font.css'
    ];
}
