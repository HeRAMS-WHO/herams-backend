<?php

use yii\web\Application;

$config = \Yii::$container->getDefinitions()[Application::class];
unset($config['modules']['debug']);
$config['bootstrap'] = array_filter($config['bootstrap'], function($value) {
    return $value !== 'debug';
});
return $config;