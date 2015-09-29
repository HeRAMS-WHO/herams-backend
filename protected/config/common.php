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
                'User' => \prime\models\User::class,
                'Profile' => \prime\models\Profile::class,
                'RegistrationForm' => \prime\models\forms\user\Registration::class,
                'RecoveryForm' => \prime\models\forms\user\Recovery::class,
                'SettingsForm' => \prime\models\forms\user\Settings::class
            ],
            'controllerMap' => [
                'registration' => \prime\controllers\RegistrationController::class,
                'recovery' => \prime\controllers\RecoveryController::class
            ],
            'admins' => [
                'joey_claessen@hotmail.com'
            ]
        ],
    ],
    'params' => [
        'system@prime.com'
    ]
];