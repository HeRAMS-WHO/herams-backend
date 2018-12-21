<?php


namespace prime\widgets\map;


use yii\web\AssetBundle;

class LeafletBundle extends AssetBundle
{

    public $css = [
        [
            'https://unpkg.com/leaflet@1.3.4/dist/leaflet.css',
            'integrity' => 'sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==',
            'crossorigin' => 'anonymous'
        ]
    ];

    public $js = [
        [
            'https://unpkg.com/leaflet@1.3.4/dist/leaflet.js',
            'integrity' => 'sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==',
            'crossorigin' => 'anonymous'
        ]
    ];

}