<?php

use prime\models\ar\Setting;

require_once __DIR__ . '/../helpers/functions.php';
return [
    'layout' => 'simple',
    'id' => 'herams',
    'name' => 'HeRAMS',
    'basePath' => realpath(__DIR__ . '/../'),
    'timeZone' => 'UTC',
    'vendorPath' => '@app/../vendor',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@prime' => '@app',
        '@views' => '@app/views',
        '@tests' => '@app/../tests',
    ],
    'bootstrap' => ['log'],
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('MYSQL_DATABASE'),
            'password' => getenv('MYSQL_PASSWORD'),
            'username' => getenv('MYSQL_USER'),
            'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'enableQueryCache' => true,
            'queryCache' => 'cache',
            'tablePrefix' => 'prime2_'
        ],
        'limesurveySSo' => [
            'class' => \prime\components\JwtSso::class,
            'errorRoute' => ['site/lime-survey'],
            'privateKey' => getenv('PRIVATE_KEY_FILE'),
            'loginUrl' => 'https://ls.herams.org/plugins/unsecure?plugin=FederatedLogin&function=SSO',
            'userNameGenerator' => function($id) {
                return "prime_$id";
            }
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
            $json = new \SamIT\LimeSurvey\JsonRpc\JsonRpcClient(Setting::get('limeSurvey.host'));
            $result = new \SamIT\LimeSurvey\JsonRpc\Client($json, Setting::get('limeSurvey.username'), Setting::get('limeSurvey.password'));
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
                'constructArgs' => [getenv('SMTP_HOST'), getenv('SMTP_PORT')]
            ]
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
            'adminPermission' => 'admin',
            'controllerMap' => [
                'security' => [
                    'class' => \dektrium\user\controllers\SecurityController::class,
                    'layout' => '//simple'
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
        ]
    ]
];
