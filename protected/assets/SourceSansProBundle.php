<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SourceSansProBundle extends AssetBundle
{
    public $baseUrl = "https://fonts.googleapis.com/";

    public $css = [
        'css?family=Source+Sans+Pro:200,300,400,600,700',
    ];
}
