<?php

namespace prime\assets;

use yii\web\AssetBundle;


class SocialAsset extends AssetBundle {

    public $css = [
        'bootstrap-social.scss'
    ];

    public $depends = [
        AppAsset::class,
        FontAwesameAsset::class
    ];

    public $js = [

    ];

    public $sourcePath = '@bower/bootstrap-social';

}