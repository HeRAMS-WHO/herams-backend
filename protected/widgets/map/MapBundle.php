<?php

namespace prime\widgets\map;

use yii\web\AssetBundle;
use yii\web\View;

class MapBundle extends AssetBundle
{
    public $depends = [
        LeafletBundle::class,

    ];

    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        [
            'https://cdn.jsdelivr.net/npm/chroma-js@2.0.2/chroma.min.js',
            'integrity' => 'sha256-A6e6m2HRvOpsUi37pgdyPYK2rbumr3kp6WcvGUMQ5Bc=',
            'crossorigin' => 'anonymous'
        ],
        [
            'https://d3js.org/d3.v3.min.js',
        ],
        [
            'https://d3js.org/d3-collection.v1.min.js',
        ],
        [
            'PopupRenderer.js',
            'position' => View::POS_HEAD
        ],
        [
            'DashboardMapRenderer.js',
            'position' => View::POS_HEAD
        ],
        [
            'PopupListRenderer.js',
            'position' => View::POS_HEAD
        ]

    ];
    public $css = [
        'map.css'
    ];
}
