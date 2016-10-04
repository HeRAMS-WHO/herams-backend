<?php
$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'controllerMap' => [
        'migrate' => \bariew\moduleMigration\ModuleMigrateController::class
    ],
    'controllerNamespace' => 'prime\\commands',
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
if (defined('YII_ENV')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/envs/' . YII_ENV . '.php'));
}
if (file_exists(__DIR__ . '/local.php')) {
    $config = yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/local.php'));
}
return $config;