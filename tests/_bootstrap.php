<?php

define('TEST_ADMIN_ID', 1);
define('TEST_USER_ID', 2);


call_user_func(function() {
    define('YII_ENV', getenv('YII_ENV'));
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('CONSOLE') or define('CONSOLE', true);

    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        die("Could not locate composer autoloader");

    }

    require_once $autoload;
    $config = require __DIR__ . '/../protected/config/codeception.php';

    \Yii::$container->set(\yii\web\Application::class, $config);


    $base = __DIR__;
    if (!is_dir($base . '/_output')) {
        mkdir($base . '/_output');
    }
});