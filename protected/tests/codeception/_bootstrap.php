<?php
// This is global bootstrap for autoloading
$loader = require __DIR__ . '/../../vendor/autoload.php';
define('YII_ENV', 'codeception');
// Set alias for configuration file.
\Yii::setAlias('@tests/codeception/config/unit.php', __DIR__ . '/../../config/web.php');