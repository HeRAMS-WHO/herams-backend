<?php


namespace prime\widgets\chart;

use yii\web\AssetBundle;

class ChartBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $js = [
        'center-text.js',
    ];

    public $depends = [
        ChartJsBundle::class
    ];
}
