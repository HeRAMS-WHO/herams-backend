<?php
require_once __DIR__ . '/../helpers/functions.php';
return [
    'layout' => 'simple',
    'id' => 'prime',
    'name' => 'Prime',
    'basePath' => realpath(__DIR__ . '/../'),
    'timeZone' => 'UTC',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@prime' => '@app',
        '@views' => '@app/views'
    ],
    'bootstrap' => ['log'],
    'components' => [
        'limesurveySSo' => [
            'class' => \prime\components\JwtSso::class,
            'errorRoute' => ['site/lime-survey'],
            'privateKeyFile' => __DIR__ . '/private.key',
            'loginUrl' => 'https://ls.herams.org/plugins/unsecure?plugin=FederatedLogin&function=SSO',
            'userNameGenerator' => function($id) {
                return 'admin';
            }
        ],
        'authClientCollection' => [
            'class' => \yii\authclient\Collection::class,
            'clients' => [
                'facebook' => [
                    'class' => \dektrium\user\clients\Facebook::class,
                    'viewOptions' => [
                        'widget' => [
                            'class' => \prime\widgets\SocialAuthItem::class,
                        ]
                    ],
                    'clientId' => '1646368368981068',
                    'clientSecret' => '616885b84a81d5abc203cfc7d462ea58'
                ],
                'google' => [
                    'class' => \dektrium\user\clients\Google::class,
                    'clientId' => '550362619218-7eng5d4jjs9esfo4ddggkdd2jl31nt3u.apps.googleusercontent.com',
                    'clientSecret' => 'Yo-fvZZ3b8D5VyzSI7VQ0TyF',
                    'viewOptions' => [
                        'widget' => [
                            'class' => \prime\widgets\SocialAuthItem::class,
                        ]
                    ],
                ],
                'linkedin' => [
                    'class' => \dektrium\user\clients\LinkedIn::class,
                    'clientId' => '77li9jqu82f1tx',
                    'clientSecret' => 'jxeT5c6EcSlf7d8w',
                    'viewOptions' => [
                        'widget' => [
                            'class' => \prime\widgets\SocialAuthItem::class,
                        ]
                    ],
                ]
            ]
        ],
        'authManager' => [
            'class' => \prime\components\AuthManager::class,
            'cache' => 'cache',
            'defaultRoles' => ['user']
        ],
        'cache' => [
            'class' => YII_DEBUG ? \yii\caching\DummyCache::class : \yii\caching\FileCache::class
        ],
//        'formatter' => [
//            'numberFormatterOptions' => [
//                NumberFormatter::MIN_FRACTION_DIGITS => 0,
//                NumberFormatter::MAX_FRACTION_DIGITS => 2,
//            ]
//
//        ],
        'limeSurvey' => function (){
            $json = new \SamIT\LimeSurvey\JsonRpc\JsonRpcClient(\prime\models\ar\Setting::get('limeSurvey.host'));
            $result = new \SamIT\LimeSurvey\JsonRpc\Client($json, \prime\models\ar\Setting::get('limeSurvey.username'), \prime\models\ar\Setting::get('limeSurvey.password'));
            $result->setCache(function($key, $value, $duration) {
                return app()->get('cache')->set($key, $value, $duration);
            }, function ($key) {
                return app()->get('cache')->get($key);
            });
            return $result;
        },
        'log' => [
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/error.log'
                ]
            ]
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => \prime\models\ar\User::class
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => \yii\i18n\DbMessageSource::class
                ]
            ]
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::class,
            'transport' => [
                'class' => Swift_SmtpTransport::class,
                'constructArgs' => ['localhost', 25]
            ]
        ]
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
                'sam@mousa.nl',
                'petragallos@who.int'
            ],
            'controllerMap' => [
                'security' => [
                    'class' => \dektrium\user\controllers\SecurityController::class,
                    'layout' => '//main'
                ]
            ],
            'mailer' => [
                'class' => \dektrium\user\Mailer::class,
                'sender' => 'prime_support@who.int',
                'confirmationSubject' => new \prime\objects\Deferred(function() {return \Yii::t('user', '{0}: Your account has successfully been activated!', ['0' => app()->name]);}),
                'recoverySubject' => new \prime\objects\Deferred(function() {return \Yii::t('user', '{0}: Password reset', ['0' => app()->name]);}),
                'welcomeSubject' => new \prime\objects\Deferred(function() {return \Yii::t('user', 'Welcome to {0}, the Public Health Risks Information Marketplace!', ['0' => app()->name]);}),
            ]
        ],
        'rbac' => [
            'class' => dektrium\rbac\RbacWebModule::class,
        ],
    ],
    'params' => [
        'defaultSettings' => [
            'icons.globalMonitor' => 'globe',
            'icons.projects' => 'tasks',
            'icons.reports' => 'file',
            'icons.preview' => 'file',
            'icons.userLists' => 'bullhorn',
            'icons.user' => 'user',
            'icons.configuration' => 'wrench',
            'icons.logIn' => 'log-in',
            'icons.logOut' => 'log-out',
            'icons.search' => 'search',
            'icons.read' => 'eye-open',
            'icons.update' => 'cog',
            'icons.share' => 'share',
            'icons.close' => 'stop',
            'icons.open' => 'play',
            'icons.remove' => 'trash',
            'icons.request' => 'forward',
            'icons.limeSurveyUpdate' => 'pencil',
            'icons.requestAccess' => 'info-sign'
        ],
        'publicKey' => file_get_contents(__DIR__ . '/public.key'),
        'privateKey' => file_get_contents(__DIR__ . '/private.key')
    ]
];
