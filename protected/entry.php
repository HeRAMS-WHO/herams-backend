<?php

defined('CONSOLE') or define('CONSOLE', false);
/**
 * Valid values are dev and production.
 * A configuration file with the same name will be included if it exists.
 */
defined('YII_ENV') or define('YII_ENV', !empty(get_cfg_var('codecept.access_log')) ? 'codeception' : require(__DIR__ . '/config/env.php'));
defined('YII_DEBUG') or define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));// && YII_ENV != 'codeception');

$loader = require_once __DIR__ . '/vendor/autoload.php';

Yii::$loader = $loader;
unset($loader);

// Set webroot so we can use it in config.

Yii::$classMap = include(__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new prime\injection\Container();

$config = require __DIR__ . '/config/web.php';
spl_autoload_register(['Yii', 'autoload'], true, false);
(new \app\components\WebApplication($config))->run();