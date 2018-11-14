<?php

define('YII_ENV', getenv('YII_ENV'));


// This is global bootstrap for autoloading
$loader = require __DIR__ . '/../vendor/autoload.php';

//codecept_debug('Creating combined sql file:');

$base = __DIR__;
if (!is_dir($base . '/_output')) {
    mkdir($base . '/_output');
}
passthru("cat $base/_data/*.sql > $base/_output/combined.sql");