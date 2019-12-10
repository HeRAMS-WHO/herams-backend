<?php

$debug = [];
if (class_exists(\yii\debug\Module::class)) {
    $debug['modules']['debug'] = [
        'dataPath' => '/tmp/debug',
        'class' => yii\debug\Module::class,
        'panels' => [
            'user' => false,
        ],
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*', '172.*.*.*'],
    ];
    if (!CONSOLE) {
        $debug['bootstrap'] = ['debug'];
    }
}

return $debug;