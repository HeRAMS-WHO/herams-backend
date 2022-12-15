<?php

declare(strict_types=1);

use herams\common\domain\user\User as ActiveRecordUser;
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
use yii\i18n\MissingTranslationEvent;
use yii\swiftmailer\Mailer;

assert(isset($env) && $env instanceof EnvironmentInterface);

require_once __DIR__ . '/../helpers/functions.php';
return [
    'id' => 'herams',
    'name' => 'HeRAMS',
    'basePath' => realpath(__DIR__ . '/../'),
    'runtimePath' => $env->getWithDefault('RUNTIME_PATH', '/tmp'),
    'timeZone' => 'UTC',
    'vendorPath' => '@app/../vendor',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@prime' => '@app',
        '@views' => '@app/views',
        '@tests' => '@app/../tests',
        '@npm' => '/node_modules',
        '@composer' => realpath(__DIR__ . '/../../vendor'),
        '@yii/debug' => '@composer/yiisoft/yii2-debug/src',
        '@kartik' => '',
    ],
    'bootstrap' => [
        'log',
    ],
    'components' => [
        'db' => \yii\db\Connection::class,
        'urlSigner' => static function () use ($env): UrlSigner {
            // Use a closure to allow lazy secret loading
            return \Yii::createObject([
                'class' => UrlSigner::class,
                'secret' => $env->getSecret('app/url_signer_secret'),
                'hmacParam' => 'h',
                'paramsParam' => 'p',
                'expirationParam' => 'e',
            ]);
        },
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
        'authManager' => static fn (AuthManager $abacManager) => new \herams\common\components\AuthManager($abacManager, ActiveRecordUser::class),
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
            'class' => \yii\i18n\I18N::class,
            'translations' => [
                'app*' => [
                    'class' => \yii\i18n\GettextMessageSource::class,
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
        'mailer' => static function () use ($env): Mailer {
            return \Yii::createObject([
                'class' => Mailer::class,
                'messageConfig' => [
                    'from' => [
                        $env->getWithDefault('MAIL_FROM', 'support@herams.org') => 'HeRAMS Support',
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
            ]);
        },
    ],
];
