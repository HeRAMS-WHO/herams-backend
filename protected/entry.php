<?php

defined('YII_ENV') or define('YII_ENV', require(__DIR__ . '/config/env.php'));
define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));
defined('CONSOLE') or define('CONSOLE', false);

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/web.php';

// Define Yii class.
class Yii extends \app\components\Yii {}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = include(__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container;
(new \app\components\WebApplication($config))->run();