<?php

use prime\components\JwtSso;
use prime\models\permissions\Permission;
use prime\modules\Api\models\Key;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\values\Authorizable;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\JsonRpcClient;
use SamIT\Yii2\abac\AccessChecker;
use SamIT\Yii2\abac\ActiveRecordRepository;
use SamIT\Yii2\abac\ActiveRecordResolver;
use yii\swiftmailer\Mailer;

/** @var \prime\components\Environment $env */
require_once __DIR__ . '/../helpers/functions.php';
ini_set('memory_limit','4096M');
return [
    'id' => 'herams',
    'name' => 'HeRAMS',
    'basePath' => realpath(__DIR__ . '/../'),
    'runtimePath' => $env->get('RUNTIME_PATH', '/tmp'),
    'timeZone' => 'UTC',
    'vendorPath' => '@app/../vendor',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@bower/bootstrap' => '@npm/bootstrap',
        '@prime' => '@app',
        '@views' => '@app/views',
        '@tests' => '@app/../tests',
    ],
    'bootstrap' => ['log'],
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=' . $env->get('DB_HOST') . ';dbname=' . $env->get('DB_NAME'),
            'password' => $env->get('DB_PASS'),
            'username' => $env->get('DB_USER'),
            'enableSchemaCache' => !YII_DEBUG,
            'schemaCache' => 'cache',
            'enableQueryCache' => true,
            'queryCache' => 'cache',
            'tablePrefix' => 'prime2_'
        ],
        'limesurveySSo' => [
            'class' => JwtSso::class,
            'errorRoute' => ['site/lime-survey'],
            'privateKey' => $env->get('PRIVATE_KEY_FILE', false) ? file_get_contents($env->get('PRIVATE_KEY_FILE')) : null,
            'loginUrl' => 'https://ls.herams.org/plugins/unsecure?plugin=FederatedLogin&function=SSO',
            'userNameGenerator' => function($id) use ($env) {
                return $env->get('SSO_PREFIX', 'prime_') . $id;
            }
        ],
        'urlSigner' => [
            'class' => \SamIT\Yii2\UrlSigner\UrlSigner::class,
            'secret' => $env->get('URL_SIGNING_SECRET'),
            'hmacParam' => 'h',
            'paramsParam' => 'p',
            'expirationParam' => 'e'
        ],
        'abacResolver' => function(): \SamIT\abac\interfaces\Resolver {
            return new \SamIT\abac\resolvers\ChainedResolver(
                new ActiveRecordResolver(),
                new \prime\components\GlobalPermissionResolver()
            );
        },
        'abacManager' => function() {
            $engine = new \SamIT\abac\engines\SimpleEngine(require __DIR__ . '/rule-config.php');
            $repo = new ActiveRecordRepository(Permission::class, [
                ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
                ActiveRecordRepository::SOURCE_NAME => 'source',
                ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
                ActiveRecordRepository::TARGET_NAME => 'target',
                ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION
            ]);
            $cachedRepo = new \SamIT\abac\repositories\CachedReadRepository($repo);

            $environment = new class extends ArrayObject implements Environment {};
            $environment['globalAuthorizable'] = new Authorizable(AccessChecker::GLOBAL, AccessChecker::BUILTIN);
            return new \SamIT\abac\AuthManager($engine, $cachedRepo, \Yii::$app->abacResolver, $environment);
        },
        'authManager' => function() {


            return new \prime\components\AuthManager(\Yii::$app->get('abacManager'), [
                'userClass' => \prime\models\ar\User::class,
                'globalId' => AccessChecker::GLOBAL,
                'globalName' => AccessChecker::BUILTIN,
                'guestName' => AccessChecker::BUILTIN,
                'guestId' => AccessChecker::GUEST,
            ]);
        },


        'limesurveyCache' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => '@runtime/limesurveyCache'
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class
        ],
//        'formatter' => [
//            'numberFormatterOptions' => [
//                NumberFormatter::MIN_FRACTION_DIGITS => 0,
//                NumberFormatter::MAX_FRACTION_DIGITS => 2,
//            ]
//
//        ],
        'limesurveyDataProvider' => [
            'class' => \prime\components\LimesurveyDataProvider::class,
            'client' => 'limesurvey',
        ],
        'limesurvey' => function () use ($env) {
            $json = new JsonRpcClient($env->get('LS_HOST'), false, 30);
            $result = new Client($json, $env->get('LS_USER'), $env->get('LS_PASS'));
            $result->setCache(function($key, $value, $duration) {
                \Yii::info('Setting cache key: ' . $key, 'ls');
                return app()->get('limesurveyCache')->set($key, $value, $duration);
            }, function ($key) {
                $result = app()->get('limesurveyCache')->get($key);
                if ($result === false) {
                    \Yii::info('Getting MISS key: ' . $key, 'ls');
                } else {
                    \Yii::info('Getting HIT key: ' . $key, 'ls');
                }
                return $result;
            });
            return $result;
        },
        'log' => [
            'flushInterval' => 1,

            'targets' => [
                [
                    'exportInterval' => 1,
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/error.log'
                ]
            ]
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'loginUrl' => '/session/create',
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
            'class' => Mailer::class,
            'messageConfig' => [
                'from' => ['support@herams.org' => 'HeRAMS Support']
            ],
            'transport' => [
                'class' => Swift_SmtpTransport::class,
                'username' => $env->get('SMTP_USER'),
                'password' => $env->get('SMTP_PASS'),
                'constructArgs' => [
                    $env->get('SMTP_HOST'),
                    $env->get('SMTP_PORT'),
                    $env->get('SMTP_ENCRYPTION')
                ]
            ]
        ],
    ],
    'modules' => [
        'api' => [
            'class' => \prime\modules\Api\Module::class,
            'components' => [
                'user' => [
                    'class' => \yii\web\User::class,
                    'enableSession' => false,
                    'identityClass'=> Key::class
                ]
            ]
        ]
//        'user' => [
//            'class' => \dektrium\user\Module::class,
//            'layout' => '//map-popover',
//            'controllerMap' => [
//                'admin' => [
//                    'class' => \dektrium\user\controllers\AdminController::class,
//                    'layout' => '//admin'
//                ],
//                'registration' => [
//                    'class' => RegistrationController::class,
//                    'on ' . RegistrationController::EVENT_AFTER_CONFIRM => function() {
//                        \Yii::$app->end(0, \Yii::$app->response->redirect('/'));
//                    }
//                ]
//            ],
//            'modelMap' => [
//                'User' => \prime\models\ar\User::class,
//                'Profile' => \prime\models\ar\Profile::class,
//                'RegistrationForm' => \prime\models\forms\user\Registration::class,
//                'RecoveryForm' => \prime\models\forms\user\Recovery::class,
//                'SettingsForm' => \prime\models\forms\user\Settings::class
//            ],
//            'adminPermission' => 'admin',
//            'mailer' => [
//                'class' => \dektrium\user\Mailer::class,
//                'sender' => 'support@herams.org',
//                'confirmationSubject' => new Deferred(function() {return \Yii::t('user', '{0}: Your account has successfully been activated!', ['0' => app()->name]);}),
//                'recoverySubject' => new Deferred(function() {return \Yii::t('user', '{0}: Password reset', ['0' => app()->name]);}),
//                'welcomeSubject' => new Deferred(function() {return \Yii::t('user', 'Welcome to {0}, the Health Resources and Services Availability Monitoring System', ['0' => app()->name]);}),            ]
//        ],
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
        'responseSubmissionKey' => $env->get('RESPONSE_SUBMISSION_KEY')
    ]
];
