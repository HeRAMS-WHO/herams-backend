<?php

namespace prime\assets;

use yii\web\AssetBundle;

class GoogleMapsAsset extends AssetBundle {

    public $css = [

    ];

    public $depends = [

    ];

    public $js = [
        '//maps.google.com/maps/api/js?sensor=false&libraries=places'
    ];
}