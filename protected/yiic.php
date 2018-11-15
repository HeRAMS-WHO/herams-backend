<?php

define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));
define('YII_ENV', getenv('YII_ENV'));
defined('CONSOLE') or define('CONSOLE', true);

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', file_exists(__DIR__ . '/config/debug') ? 3 : 0);
require_once __DIR__ . '/../vendor/autoload.php';
spl_autoload_register(['Yii', 'autoload'], true, true);


$config = require __DIR__ . '/config/console.php';
\Yii::$container->set(\yii\console\Application::class, $config);

\Yii::$container->get(\yii\console\Application::class)->run();
