<?php

return [
    'modules' => [
        'debug' => [
            'class' => yii\debug\Module::class,
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.*', '*'],
            'panels' => [
                'profiling' => [
                    'class' => app\components\ProfilingPanel::class
                ]
            ]
        ]
    ],
    'bootstrap' => !CONSOLE ? ['debug'] : []
];
