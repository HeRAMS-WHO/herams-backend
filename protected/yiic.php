<?php

define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));
defined('CONSOLE') or define('CONSOLE', true);

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', file_exists(__DIR__ . '/config/debug') ? 3 : 0);

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$container = new prime\injection\Container();

$config = require __DIR__ . '/config/console.php';

(new \app\components\ConsoleApplication($config))->run();
