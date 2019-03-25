<?php

/** @var \prime\components\Environment $env */

use prime\models\ar\Setting;

$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\commands',
    'controllerMap' => [
        'cache' => \prime\commands\CacheController::class
    ],
    'aliases' => [
        // Mainly for console apps.
        '@web' => '/',
        '@webroot' => realpath(__DIR__ . '/../../public')
    ],
    'components' => [
        'limesurvey' => function (){
            $json = new \SamIT\LimeSurvey\JsonRpc\JsonRpcClient(Setting::get('limeSurvey.host'), false, 30);
            $result = new \SamIT\LimeSurvey\JsonRpc\Client($json, Setting::get('limeSurvey.username'), Setting::get('limeSurvey.password'));
            $result->setCache(function($key, $value, $duration) {
                \Yii::info('Setting cache key: ' . $key, 'ls');
                return app()->get('limesurveyCache')->set($key, $value, $duration);
            }, function ($key) {
                // Disable getting anything from the limesurvey cache in the console.
                return false;
            });
            return $result;
        },
    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}
if (file_exists(__DIR__ . '/local.php')) {
    $config = yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/local.php'));
}
return $config;