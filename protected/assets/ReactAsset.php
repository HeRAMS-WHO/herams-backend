<?php

namespace prime\assets;

use yii\web\AssetBundle;

class ReactAsset extends AssetBundle
{
    public $basePath = '@webroot/react';
    public $baseUrl = '@web/react';

    public function init()
    {
        $this->setSourceBasedOnHost();
        $this->registerLocalStorageScripts();

        parent::init();
    }

    private function setSourceBasedOnHost(): void
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
    }

    private function registerLocalStorageScripts(): void
    {
        $appVersion = \Yii::$app->getAppVersion();
        $userLanguage = $this->getUserLanguage();

        $script = <<<JS
            const currentLanguage = localStorage.getItem('selectedLanguage');
            if (currentLanguage !== '{$userLanguage}') {
                localStorage.setItem('selectedLanguage', '{$userLanguage}');
            }

            // Check and store app version in the localStorage if it's different or missing
            const storedAppVersion = localStorage.getItem('appVersion');
            if (!storedAppVersion || storedAppVersion !== '{$appVersion}') {
                localStorage.setItem('appVersion', '{$appVersion}');
            }
        JS;
        \Yii::$app->view->registerJs($script, \yii\web\View::POS_READY);
    }

    private function getUserLanguage(): string
    {
        return \Yii::$app->language ?? 'en';
    }

    private function getReactJsFiles(): array
    {
        $path = \Yii::getAlias('@webroot/react/static/js');
        $files = glob("$path/*.js");
        return array_map(function ($file) {
            return 'static/js/' . basename($file);
        }, $files);
    }

    private function getReactCssFiles(): array
    {
        $path = \Yii::getAlias('@webroot/react/static/css');
        $files = glob("$path/*.css");
        return array_map(function ($file) {
            return 'static/css/' . basename($file);
        }, $files);
    }
}
