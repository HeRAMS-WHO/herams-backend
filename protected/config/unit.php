<?php
defined('CONSOLE') or define('CONSOLE', true);
\Yii::$container = new \yii\di\Container();
return require __DIR__ . '/web.php';