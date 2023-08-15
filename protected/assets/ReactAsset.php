<?php

namespace prime\assets;

use yii\web\AssetBundle;

class ReactAsset extends AssetBundle
{
    public $basePath = '@webroot/react';
    public $baseUrl = '@web/react';

    public function init()
    {
        $host = $_SERVER['HTTP_HOST'];

        if ($host === 'herams.test') {
            // For development environment, point to React development server
            $this->baseUrl = 'https://react.herams.test/';
            $this->js = ['https://react.herams.test/static/js/bundle.js'];
            $this->css = [];  // Typically, in development, CSS is also included in the JS bundle
        } else {
            $this->js = $this->getReactJsFiles();
            $this->css = $this->getReactCssFiles();
        }
        parent::init();
    }

    private function getReactJsFiles(): array
    {
        $path = \Yii::getAlias('@webroot/react/static/js');
        $files = glob("$path/*.js");
        return array_map(function($file) {
            return 'static/js/' . basename($file);
        }, $files);
    }

    private function getReactCssFiles(): array
    {
        $path = \Yii::getAlias('@webroot/react/static/css');
        $files = glob("$path/*.css");
        return array_map(function($file) {
            return 'static/css/' . basename($file);
        }, $files);
    }
}
