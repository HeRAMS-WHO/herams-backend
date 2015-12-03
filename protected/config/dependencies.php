<?php

/** @var \yii\di\Container $container */
$container = \Yii::$container;

$container->setSingleton(\SamIT\LimeSurvey\JsonRpc\Client::class, function() {
    return \Yii::$app->get('limeSurvey');
});