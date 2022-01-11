<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class JqueryBundle extends AssetBundle
{
    public $baseUrl = '@npm/jquery/dist';
    public $js = [
        'jquery.min.js',
    ];
}
