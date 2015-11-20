<?php

namespace prime\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class GoogleMapsAsset extends AssetBundle {

    public $css = [

    ];

    public $depends = [

    ];

    public $js = [
        'http://maps.google.com/maps/api/js?sensor=false&libraries=places'
    ];

    public $sourcePath = false;
}