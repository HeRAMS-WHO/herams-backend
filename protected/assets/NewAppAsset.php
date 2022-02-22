<?php

namespace prime\assets;

use yii\web\AssetBundle;

class NewAppAsset extends AssetBundle
{
    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        IconBundle::class,
        SourceSansProBundle::class,
        FormBundle::class,
        ToastBundle::class,
        MainBundle::class,
    ];
}
