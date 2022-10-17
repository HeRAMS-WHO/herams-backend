<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class AnimateCssBundle extends AssetBundle
{
    public $baseUrl = '@npm/animate.css';

    public $css = [
        'animate.min.css',
    ];
}
