<?php

namespace prime\assets;

use herams\common\helpers\ConfigurationProvider;
use yii\web\AssetBundle;

class ReactAsset extends AssetBundle
{
    public $basePath = '@webroot/react';

    public $baseUrl = '@web/react';

    private ConfigurationProvider $configurationProvider;

    public function init()
    {
        $this->configurationProvider = \Yii::$container->get(ConfigurationProvider::class);

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
//            $this->css = $this->getReactCssFiles();
        }
    }

    private function registerLocalStorageScripts(): void
    {
        $appVersion = \Yii::$app->getAppVersion();
        $userLanguage = $this->getUserLanguage();
        $apiProxyUrl = 'https://herams.test/api-proxy/core/';
        $apiUrl = 'https://herams.test/';

        $localizedLanguages = $this->configurationProvider->getLocalizedLanguageNames($userLanguage);
        $languagesJson = json_encode($localizedLanguages);

        $script = <<<JS
            const storedLanguage = localStorage.getItem('selectedLanguage');
            const storedAppVersion = localStorage.getItem('appVersion');
            const storedLanguages = localStorage.getItem('availableLanguages');
            
            if (storedLanguage !== '{$userLanguage}') {
                localStorage.setItem('selectedLanguage', '{$userLanguage}')
            }
            if (!storedLanguages || storedLanguages !== '{$languagesJson}') {
                localStorage.setItem('availableLanguages', '{$languagesJson}');
            }
            if (!storedAppVersion || storedAppVersion !== '{$appVersion}') {
                localStorage.setItem('appVersion', '{$appVersion}');
            }
            
            window.HERAMS_PROXY_API_URL = '{$apiProxyUrl}';
            window.HERAMS_API_URL = '{$apiUrl}';
        JS;

        \Yii::$app->view->registerJs($script, \yii\web\View::POS_READY);
    }

    private function getUserLanguage(): string
    {
        return \Yii::$app->language ?? 'en';
    }

    private function getReactJsFiles(): array
    {
        $path = \Yii::getAlias('@webroot/react');
        $files = glob("$path/*.js");
        return array_map(function ($file) {
            return '/' . basename($file);
        }, $files);
    }

//    private function getReactCssFiles(): array
//    {
//        $path = \Yii::getAlias('@webroot/react/static/css');
//        $files = glob("$path/*.css");
//        return array_map(function ($file) {
//            return 'static/css/' . basename($file);
//        }, $files);
//    }
}
