<?php

use prime\components\Environment;

defined('CONSOLE') or define('CONSOLE', false);
/**
 * Valid values are dev and production.
 * A configuration file with the same name will be included if it exists.
 */
define('YII_ENV', getenv('YII_ENV'));

defined('YII_DEBUG') or define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));


call_user_func(function () {
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        die("Could not locate composer autoloader");
    }

    require_once $autoload;
    $env = new Environment(__DIR__ . '/config/env.json', '/run/config/config.json', '/run/env.json');
    \Yii::$container->setDefinitions(require __DIR__ . '/config/di.php');
    $config = require __DIR__ . '/config/web.php';
    \Yii::$container->set(\yii\web\Application::class, $config);
    $app = \Yii::$container->get(\yii\web\Application::class);
    $app->run();
});
