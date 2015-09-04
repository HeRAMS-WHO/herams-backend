<?php
/**
 * User: Sam}
 * Date: 9/4/15
 * Time: 12:24 PM
 */

namespace app\assets;


use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $css = [
        'scss/main.scss'
    ];

    public $depends = [
        BootstrapAsset::class
    ];

}