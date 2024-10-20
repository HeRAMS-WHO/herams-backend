<?php

declare(strict_types=1);

use herams\common\models\PermissionOld;

$debug = [];
if (class_exists(\yii\debug\Module::class)) {
    if (! CONSOLE) {
        $debug['modules']['debug'] = [
            'dataPath' => '/debugdata',
            'as ' . \yii\filters\AccessControl::class => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [PermissionOld::PERMISSION_DEBUG_TOOLBAR],
                    ],
                    [
                        'allow' => true,
                    ],
                ],
            ],
            'class' => yii\debug\Module::class,
            'allowedIPs' => ['*'],
        ];

        $debug['bootstrap'] = ['debug'];
    }
}

return $debug;
