<?php

use prime\models\ar\Permission;

$debug = [];
if (class_exists(\yii\debug\Module::class)) {
    if (!CONSOLE) {
        $debug['modules']['debug'] = [
            'dataPath' => '/tmp/debug',
            'as ' . \yii\filters\AccessControl::class => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Permission::PERMISSION_DEBUG_TOOLBAR]
                    ],
                    [
                        'allow' => false,
                    ]
                ]
            ],
            'class' => yii\debug\Module::class,
            'allowedIPs' => ['*'],
        ];

        $debug['bootstrap'] = ['debug'];
    }
}

return $debug;
