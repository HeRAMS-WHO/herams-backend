<?php

include __DIR__ . '/../helpers/functions.php';

return [
    'id' => 'prime',
    'name' => 'Prime 2.0',
    'basePath' => realpath(__DIR__ . '/../'),
    'timeZone' => 'UTC',
    'sourceLanguage' => 'en',

    'components' => [
        'user' => [
            'class' => \dektrium\user\Module::class,
            'modelMap' => [
                'User' => \app\models\User::class
            ],
            'identityClass' => \app\models\User::class
        ],
    ]
];