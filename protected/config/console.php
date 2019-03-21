<?php

/** @var \prime\components\Environment $env */

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

    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}
if (file_exists(__DIR__ . '/local.php')) {
    $config = yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/local.php'));
}
return $config;