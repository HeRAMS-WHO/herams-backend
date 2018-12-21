<?php


namespace prime\widgets\map;


use yii\web\AssetBundle;

class MapBundle extends AssetBundle
{

    public $depends = [
        LeafletBundle::class
    ];

    public $sourcePath = __DIR__ . '/assets';

    public $css = [
        'map.css'
    ];

}