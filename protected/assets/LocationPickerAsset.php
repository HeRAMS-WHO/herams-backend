<?php

namespace prime\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class LocationPickerAsset extends AssetBundle {

    public $css = [

    ];

    public $depends = [
        JqueryAsset::class,
        GoogleMapsAsset::class
    ];

    public $js = [
        'dist/locationpicker.jquery.min.js'
    ];

    public $sourcePath = '@bower/jquery-locationpicker-plugin';
}