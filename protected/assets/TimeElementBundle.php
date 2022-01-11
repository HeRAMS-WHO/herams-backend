<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

/**
 * A bundle for github's time elements
 * @see https://github.com/github/time-elements
 * @package prime\assets
 */
class TimeElementBundle extends AssetBundle
{
    public $baseUrl = '@npm/@github/time-elements/dist';
    public $js = [
        'index.js',
    ];

    public $jsOptions = [
        'type' => 'module'
    ];
}
