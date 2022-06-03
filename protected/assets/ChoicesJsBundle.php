<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class ChoicesJsBundle extends AssetBundle
{
    public $baseUrl = '@npm/choices.js/public/assets';

    public $js = [
        'knockout-latest.js',
    ];
}
