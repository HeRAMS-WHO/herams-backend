<?php

namespace prime\assets;

use app\assets\FontBundle;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\FileHelper;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class SassAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-sass/assets';
    public $css = [
        'stylesheets/main.scss'
    ];

    public $depends = [
        BootstrapAsset::class,
        FontBundle::class,
        YiiAsset::class
    ];

    private function hasDirChanged($source, $target)
    {
        foreach(FileHelper::findFiles($source) as $sourceFile) {
            $targetFile = str_replace($source, $target, $sourceFile);
            if ($this->hasFileChanged($sourceFile, $targetFile)) {
                return true;
            }
        }
        return false;
    }

    private function hasFileChanged($source, $target)
    {
        return !file_exists($target) || md5_file($source) != md5_file($target);
    }

    public function publish($am)
    {
        if (isset($this->sourcePath)) {
            list ($this->basePath, $this->baseUrl) = $am->publish($this->sourcePath, $this->publishOptions);
            $copy = [
                __DIR__ . '/scss/main.scss' => $this->basePath . '/stylesheets/main.scss',

            ];
            foreach ($copy as $source => $target) {
                if (is_dir($source) && $this->hasDirChanged($source, $target)) {
                    FileHelper::copyDirectory($source, $target);
                } elseif ($this->hasFileChanged($source, $target)) {
                    copy($source, $target);
                }

            }
        }
        parent::publish($am);


    }


}