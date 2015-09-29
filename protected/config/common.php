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

        ],
        'authClientCollection' => [
            'class' => \yii\authclient\Collection::class,
            'clients' => [
                'facebook' => [
                    'class' => \dektrium\user\clients\Facebook::class,
                    'clientId' => '674518435950722',
                    'clientSecret' => '35ebd72128626fab6afe820321565583'
                ],
                'google' => [
                    'class' => \dektrium\user\clients\Google::class,
                    'clientId' => '550362619218-7eng5d4jjs9esfo4ddggkdd2jl31nt3u.apps.googleusercontent.com',
                    'clientSecret' => 'Yo-fvZZ3b8D5VyzSI7VQ0TyF'
                ]
            ]
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