<?php
include __DIR__ . '/../helpers/functions.php';
return [
    'id' => 'prime',
    'name' => 'Prime 2.0',
    'basePath' => realpath(__DIR__ . '/../'),
    'timeZone' => 'UTC',
    'sourceLanguage' => 'en',
    'aliases' => [
        '@prime' => '@app'
    ],
    'components' => [

        'authClientCollection' => [
            'class' => \yii\authclient\Collection::class,
            'clients' => [
                'facebook' => [
                    'class' => \dektrium\user\clients\Facebook::class,
                    'clientId' => '1646368368981068',
                    'clientSecret' => '616885b84a81d5abc203cfc7d462ea58'
                ],
                'google' => [
                    'class' => \dektrium\user\clients\Google::class,
                    'clientId' => '550362619218-7eng5d4jjs9esfo4ddggkdd2jl31nt3u.apps.googleusercontent.com',
                    'clientSecret' => 'Yo-fvZZ3b8D5VyzSI7VQ0TyF'
                ],
                'linkedin' => [
                    'class' => \dektrium\user\clients\LinkedIn::class,
                    'clientId' => '77li9jqu82f1tx',
                    'clientSecret' => 'jxeT5c6EcSlf7d8w'
                ]
            ]
        ],
        'authManager' => [
            'class' => \dektrium\rbac\components\DbManager::class
        ],
        'limesurvey' => function (){
            $json = new \SamIT\LimeSurvey\JsonRpc\JsonRpcClient('http://whols2.befound.nl/index.php?r=admin/remotecontrol');
            return new \SamIT\LimeSurvey\JsonRpc\Client($json, 'prime', 'H9y43n4X');
        },
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => \prime\models\ar\User::class
        ],
    ],
    'modules' => [
        'user' => [
            'class' => \dektrium\user\Module::class,
            'modelMap' => [
                'User' => \prime\models\ar\User::class,
                'Profile' => \prime\models\ar\Profile::class,
                'RegistrationForm' => \prime\models\forms\user\Registration::class,
                'RecoveryForm' => \prime\models\forms\user\Recovery::class,
                'SettingsForm' => \prime\models\forms\user\Settings::class
            ],
            'admins' => [
                'joey_claessen@hotmail.com',
                'sam@mousa.nl',
                'petragallos@who.int'
            ]
        ],
        'rbac' => [
            'class' => dektrium\rbac\Module::class,
        ],
    ],
    'params' => [
        'system@prime.com'
    ]
];