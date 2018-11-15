<?php

define('YII_ENV', getenv('YII_ENV'));
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('CONSOLE') or define('CONSOLE', true);
call_user_func(function() {
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        die("Could not locate composer autoloader");

    }

    require_once $autoload;
    $config = require __DIR__ . '/../protected/config/web.php';

    \Yii::$container->set(\yii\web\Application::class, $config);


    $base = __DIR__;
    if (!is_dir($base . '/_output')) {
        mkdir($base . '/_output');
    }
});