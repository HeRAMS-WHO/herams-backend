<?php


namespace prime\widgets\map;


use yii\web\AssetBundle;

class LeafletBundle extends AssetBundle
{

    public $css = [
        [
            'url' => 'https://unpkg.com/leaflet@1.4.0/dist/leaflet.css',
            'integrity' => "sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==",
            'crossorigin' => 'anonymous'
        ]
    ];

    public $js = [
        [
            "https://unpkg.com/leaflet@1.4.0/dist/leaflet.js",
            'integrity' => "sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==",
            'crossorigin' => 'anonymous'
        ]
    ];

}