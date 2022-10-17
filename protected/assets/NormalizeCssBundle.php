<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class NormalizeCssBundle extends AssetBundle
{
    public $baseUrl = '@npm/normalize.css';

    public $css = [
        'normalize.css',
    ];
}
