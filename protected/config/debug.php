<?php

return [
    'modules' => [
        'debug' => [
            'dataPath' => '/tmp/debug',
            'class' => yii\debug\Module::class,
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.*'],
        ]
    ],
    'bootstrap' => !CONSOLE ? ['debug'] : []
];
