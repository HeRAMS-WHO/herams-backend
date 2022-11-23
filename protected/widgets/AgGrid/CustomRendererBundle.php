<?php

declare(strict_types=1);

namespace prime\widgets\AgGrid;

use prime\assets\AnimateCssBundle;
use yii\web\AssetBundle;

class CustomRendererBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets/';

    public $js = [
        'ToggleButtonRenderer.js',
        'ToggleButtonFilter.js',
    ];

    public $css = [
        'customizations.css',
    ];

    public $depends = [
        AnimateCssBundle::class,
    ];
}
