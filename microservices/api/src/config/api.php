<?php

declare(strict_types=1);

use herams\common\components\AuditService;
use herams\common\components\Formatter;
use herams\common\config\CommonConfigurator;
use herams\common\interfaces\EnvironmentInterface;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\interfaces\RuleEngine;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\abac\values\Authorizable;
use SamIT\Yii2\abac\AccessChecker;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\i18n\GettextMessageSource;
use yii\i18n\I18N;
use yii\i18n\MissingTranslationEvent;
use yii\swiftmailer\Mailer;
use yii\web\User;

return function(EnvironmentInterface $env, \yii\di\Container $container) : array {
    $commonDiConfigurator = new CommonConfigurator();
    $commonDiConfigurator->configure($env, $container);


    $diConfigurator = require __DIR__ . '/di.php';
    $diConfigurator($env, $container);

    $config = [
        'id' => 'herams-api',
        'name' => 'HeRAMS API',
        'basePath' => realpath(__DIR__ . '/../'),
        'runtimePath' => $env->get('RUNTIME_PATH', '/tmp'),
        'timeZone' => 'UTC',
        'vendorPath' => '@app/../vendor',
        'sourceLanguage' => 'en-US',
        'aliases' => [
            '@tests' => '@app/../tests',
            '@composer' => realpath(__DIR__ . '/../../vendor'),
            '@yii/debug' => '@composer/yiisoft/yii2-debug/src',
        ],
        'bootstrap' => [
            'log',
            'auditService',
        ],
        'components' => [
            'auditService' => AuditService::class,
            'formatter' => [
                'class' => Formatter::class,
            ],
            'urlManager' => \yii\di\Instance::ensure('apiUrlManager', \yii\web\UrlManager::class),
            'user' => [
                'class' => \yii\web\User::class,
                'identityClass' => \herams\common\domain\user\User::class,
                'enableSession' => false,
                'loginUrl' => null,

            ],
            'request' => [
                'class' => \yii\web\Request::class,
                'enableCsrfValidation' => false,
                'enableCsrfCookie' => false,
                'trustedHosts' => [
                    '10.0.0.0/8',
                    '172.0.0.0/8',
                ],
                // To enable rendering in tests.
                'scriptFile' => realpath(__DIR__ . '/../../public/index.php'),
                'scriptUrl' => '/',
                'parsers' => [
                    'application/json' => yii\web\JsonParser::class,
                ],
            ],
            'response' => [
                'class' => \yii\web\Response::class,
                'formatters' => [
                    \yii\web\Response::FORMAT_JSON => [
                        'class' => \yii\web\JsonResponseFormatter::class,
                        'prettyPrint' => true,
                    ],
                ],
            ],
//        'assetManager' => [
//            'class' => \herams\api\components\DummyAssetManager::class
//
//        ],
            'db' => [
                'class' => \yii\db\Connection::class,
                'charset' => 'utf8mb4',
                'dsn' => $env->getWrappedSecret('database/dsn'),
                'password' => $env->getWrappedSecret('database/password'),
                'username' => $env->getWrappedSecret('database/username'),
                'enableSchemaCache' => ! YII_DEBUG,
                'schemaCache' => 'cache',
                'enableQueryCache' => true,
                'queryCache' => 'cache',
                'tablePrefix' => 'prime2_',
            ],
            'urlSigner' => [
                'class' => UrlSigner::class,
                'secret' => $env->getSecret('app/url_signer_secret'),
                'hmacParam' => 'h',
                'paramsParam' => 'p',
                'expirationParam' => 'e',
            ],
            'preloadingSourceRepository' => PreloadingSourceRepository::class,
            'abacManager' => static function (
                Resolver $resolver, // Taken from container
                RuleEngine $engine, // Taken from container
                PermissionRepository $preloadingSourceRepository  // Taken from app
            ) {
                $environment = new class() extends ArrayObject implements Environment {
                };
                $environment['globalAuthorizable'] = new Authorizable(AccessChecker::GLOBAL, AccessChecker::BUILTIN);
                return new AuthManager($engine, $preloadingSourceRepository, $resolver, $environment);
            },
            'authManager' => static fn (AuthManager $abacManager) => new \herams\common\components\AuthManager($abacManager, \herams\common\domain\user\User::class),
            'check' => static function (User $user) {
                assert($user === \Yii::$app->user);
                return new \herams\common\services\UserAccessCheck($user);
            },
            'cache' => [
                'class' => \yii\caching\CacheInterface::class,
            ],
            //        'formatter' => [
            //            'numberFormatterOptions' => [
            //                NumberFormatter::MIN_FRACTION_DIGITS => 0,
            //                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            //            ]
            //
            //        ],
            'log' => [
                'flushInterval' => 1,
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets' => [
                    [
                        'exportInterval' => 1,
                        'class' => \yii\log\FileTarget::class,
                        'levels' => ['error', 'warning'],
                        'logFile' => '@runtime/logs/error.log',
                    ],
                ],
            ],
            'i18n' => [
                'class' => I18N::class,
                'translations' => [
                    'app*' => [
                        'class' => GettextMessageSource::class,
                        'useMoFile' => false,
                        'basePath' => '@vendor/herams/i18n/locales',
                        'catalog' => 'LC_MESSAGES/app',
                        'on ' . \yii\i18n\MessageSource::EVENT_MISSING_TRANSLATION => static function (MissingTranslationEvent $event) {
                            if (YII_DEBUG) {
                                $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
                            }
                        },
                    ],
                ],
            ],
            'mailer' => [
                'class' => Mailer::class,
                'messageConfig' => [
                    'from' => [
                        $env->get('MAIL_FROM', 'support@herams.org') => 'HeRAMS Support',
                    ],
                ],
                'transport' => [
                    'class' => Swift_SmtpTransport::class,
                    'username' => $env->getWrappedSecret('smtp/username'),
                    'password' => $env->getWrappedSecret('smtp/password'),
                    'constructArgs' => [
                        $env->getWrappedSecret('smtp/host'),
                        $env->getSecret('smtp/port'),
                        $env->getWrappedSecret('smtp/encryption'),
                    ],
                ],
            ],

        ],
        'controllerNamespace' => 'herams\\api\\controllers',

        'defaultRoute' => 'marketplace/herams',
    ];

    if (YII_DEBUG) {
        $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
    }

    $container->set(\yii\web\Application::class, $config);
    return $config;

};
