<?php

namespace prime\assets;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\AssetManager;
use yii\web\YiiAsset;

class SassAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-sass/assets';
    public $css = [
        'stylesheets/main.scss'
    ];

    public $depends = [
        BootstrapAsset::class,
        YiiAsset::class
    ];

    public function publish($am)
    {
        list ($this->basePath, $this->baseUrl) = $am->publish($this->sourcePath, $this->publishOptions);
        $target = $this->basePath . '/stylesheets/main.scss';
        $source = __DIR__ . '/scss/main.scss';
        if (!file_exists($target)
            || md5_file($source) != md5_file($target)) {
            copy($source, $target);
        }
        parent::publish($am);


    }


}