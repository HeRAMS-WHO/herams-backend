<?php

namespace prime\widgets\chart;

use yii\web\AssetBundle;

class ChartJsBundle extends AssetBundle
{
    public $js = [
        [
            'https://cdn.jsdelivr.net/npm/chroma-js@2.0.2/chroma.min.js',
            'integrity' => 'sha256-A6e6m2HRvOpsUi37pgdyPYK2rbumr3kp6WcvGUMQ5Bc=',
            'crossorigin' => 'anonymous'
        ],
        [
            'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js',
            'integrity' => 'sha256-MZo5XY1Ah7Z2Aui4/alkfeiq3CopMdV/bbkc/Sh41+s=',
            'crossorigin' => 'anonymous'
        ]
    ];
}
