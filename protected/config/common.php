<?php

include __DIR__ . '/../helpers/functions.php';

return [
    'id' => 'prime',
    'name' => 'Prime 2.0',
    'basePath' => realpath(__DIR__ . '/../'),
    'timeZone' => 'UTC',
    'sourceLanguage' => 'en',

    'components' => [
        'limesurvey' => [
            'class' => \Befound\ApplicationComponents\LimeSurvey::class,
            'url' => 'http://demo.befound.nl/index.php?r=admin/remotecontrol',
            'username' => 'admin',
            'password' => 'test'

        ]
    ],

    'modules' => [
        'user' => [
            'class' => \dektrium\user\Module::class,
            'modelMap' => [
                'User' => \app\models\User::class
            ],

        ],
    ]
];