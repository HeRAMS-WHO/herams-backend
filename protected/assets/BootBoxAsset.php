<?php

namespace prime\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class BootBoxAsset extends AssetBundle {

    public $css = [

    ];

    public $depends = [
        JqueryAsset::class
    ];

    public $js = [
        'bootbox.js',
    ];

    public $sourcePath = '@bower/bootbox';

}