<?php

namespace prime\widgets\map;

use yii\web\AssetBundle;

class LeafletBundle extends AssetBundle
{
    public $css = [
        [
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/leaflet.css',
            'integrity' => "sha512-mD70nAW2ThLsWH0zif8JPbfraZ8hbCtjQ+5RU1m4+ztZq6/MymyZeB55pWsi4YAX+73yvcaJyk61mzfYMvtm9w==",
            'crossorigin' => 'anonymous',
        ],
        [
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css',
            'integrity' => 'sha512-mQ77VzAakzdpWdgfL/lM1ksNy89uFgibRQANsNneSTMD/bj0Y/8+94XMwYhnbzx8eki2hrbPpDm0vD0CiT2lcg==',
            'crossorigin' => 'anonymous',
        ],
    ];

    public $js = [
        [
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/leaflet.js",
            'integrity' => "sha512-Dqm3h1Y4qiHUjbhxTuBGQsza0Tfppn53SHlu/uj1f+RT+xfShfe7r6czRf5r2NmllO2aKx+tYJgoxboOkn1Scg==",
            'crossorigin' => 'anonymous',
        ],
        [
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js',
            'integrity' => 'sha512-OFs3W4DIZ5ZkrDhBFtsCP6JXtMEDGmhl0QPlmWYBJay40TT1n3gt2Xuw8Pf/iezgW9CdabjkNChRqozl/YADmg==',
            'crossorigin' => 'anonymous',
        ],
    ];
}
