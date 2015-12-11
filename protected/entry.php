<?php

defined('CONSOLE') or define('CONSOLE', false);
defined('YII_ENV') or define('YII_ENV', require(__DIR__ . '/config/env.php'));
define('YII_DEBUG', file_exists(__DIR__ . '/config/debug') && YII_ENV != 'codeception');

$loader = require_once __DIR__ . '/vendor/autoload.php';


// Define Yii class.
class Yii extends \app\components\Yii {
    public static $loader;
}

Yii::$loader = $loader;
unset($loader);

// Set webroot so we can use it in config.

Yii::setAlias('@webroot', realpath(__DIR__ . '/../public/'));
Yii::$classMap = include(__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new prime\injection\Container();

$config = require __DIR__ . '/config/web.php';
spl_autoload_register(['Yii', 'autoload'], true, false);
(new \app\components\WebApplication($config))->run();