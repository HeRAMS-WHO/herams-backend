<?php


namespace prime\widgets\chart;

use yii\web\AssetBundle;

class ChartJsBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        [
            'chroma.min.js',
        ],
        [
            'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js',
            'integrity' => 'sha256-MZo5XY1Ah7Z2Aui4/alkfeiq3CopMdV/bbkc/Sh41+s=',
            'crossorigin' => 'anonymous'
        ]
    ];
}
