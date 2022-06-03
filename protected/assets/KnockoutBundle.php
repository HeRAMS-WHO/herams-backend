<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class KnockoutBundle extends AssetBundle
{
    public $baseUrl = '@npm/knockout/build/output/';

    public $js = [
        'knockout-latest.js',
    ];
}
