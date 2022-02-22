<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class PrettyCheckbox extends AssetBundle
{
    public $baseUrl = '@npm/pretty-checkbox/dist';
    public $css = [
        YII_DEBUG ? 'pretty-checkbox.css' : 'pretty-checkbox.min.css'
    ];
}
