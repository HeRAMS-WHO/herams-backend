<?php
/**
 * User: Sam}
 * Date: 9/4/15
 * Time: 12:24 PM
 */

namespace app\assets;


use yii\bootstrap\BootstrapAsset;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\AssetManager;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-sass/assets';
    public $css = [
//        'less/bootstrap.less',
        'stylesheets/main.scss'
    ];

    public $depends = [
        BootstrapAsset::class
    ];

    public function publish($am)
    {
        list ($this->basePath, $this->baseUrl) = $am->publish($this->sourcePath, $this->publishOptions);

        copy(__DIR__ . '/scss/main.scss', $this->basePath . '/stylesheets/main.scss');
        parent::publish($am);


    }


}