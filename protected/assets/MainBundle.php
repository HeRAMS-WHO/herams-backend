<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class MainBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/main.css',
    ];

    public $js = [
    ];
}
