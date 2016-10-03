<?php

defined('CONSOLE') or define('CONSOLE', false);
/**
 * Valid values are dev and production.
 * A configuration file with the same name will be included if it exists.
 */
defined('YII_ENV') or define('YII_ENV', !empty(getenv('YII_ENV')) ? getenv('YII_ENV') : require(__DIR__ . '/config/env.php'));

defined('YII_DEBUG') or define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));


call_user_func(function() {

    $loader = require_once __DIR__ . '/vendor/autoload.php';

    Yii::$loader = $loader;
    unset($loader);

    Yii::$container = new prime\injection\Container();

    $config = require __DIR__ . '/config/web.php';
    spl_autoload_register(['Yii', 'autoload'], true, false);

    $app = new \app\components\WebApplication($config);

    $app->run();
});
