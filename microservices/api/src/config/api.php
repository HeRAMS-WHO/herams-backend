<?php

declare(strict_types=1);

use herams\api\domain\project\ProjectHydrator;
use herams\api\domain\workspace\WorkspaceHydrator;
use herams\common\components\AuditService;
use herams\common\components\Formatter;
use herams\common\config\CommonConfigurator;
use herams\common\domain\user\User;
use herams\common\domain\userRole\UserRoleHydrator;
use herams\common\extensions\ExtendedGettextMessageSource;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\EnvironmentInterface;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\interfaces\RuleEngine;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\abac\values\Authorizable;
use SamIT\Yii2\abac\AccessChecker;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\di\Container;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\i18n\GettextMessageSource;
use yii\i18n\I18N;
use yii\i18n\MessageSource;
use yii\i18n\MissingTranslationEvent;
use yii\log\FileTarget;
use yii\web\Application;
use yii\web\JsonResponseFormatter;
use yii\web\Request;
use yii\web\Response;
use yii\web\UrlManager;

return function (
    EnvironmentInterface $env,
    Container $container
): void {
    $commonDiConfigurator = new CommonConfigurator();
    $commonDiConfigurator->configure($env, $container);

    $hydratorDefinition = $container->getDefinitions(
    )[ActiveRecordHydratorInterface::class];
    $container->set(
        ActiveRecordHydratorInterface::class,
        static function () use ($hydratorDefinition) {
            $result = $hydratorDefinition();
            $result->registerAttributeStrategy(new ProjectHydrator(), true);
            $result->registerAttributeStrategy(new WorkspaceHydrator(), true);
            $result->registerAttributeStrategy(new UserRoleHydrator(), true);
            return $result;
        }
    );

    $diConfigurator = require __DIR__.'/di.php';
    $diConfigurator($env, $container);

    $config = [
        'id'                  => 'herams-api',
        'name'                => 'HeRAMS API',
        'basePath'            => realpath(__DIR__.'/../'),
        'runtimePath'         => $env->getWithDefault('RUNTIME_PATH', '/tmp'),
        'timeZone'            => 'CET',
        'vendorPath'          => '@app/../vendor',
        'sourceLanguage'      => 'en',
        'language'            => 'en',
        'class'               => '\herams\common\components\Application',
        'aliases'             => [
            '@tests'     => '@app/../tests',
            '@composer'  => realpath(__DIR__.'/../../vendor'),
            '@yii/debug' => '@composer/yiisoft/yii2-debug/src',
        ],
        'bootstrap'           => [
            'log',
            'auditService',
        ],
        'components'          => [
            'auditService'               => AuditService::class,
            'formatter'                  => [
                'class' => Formatter::class,
            ],
            'urlManager'                 => Instance::ensure(
                'apiUrlManager',
                UrlManager::class
            ),
            'user'                       => [
                'class'         => \yii\web\User::class,
                'identityClass' => User::class,
                'enableSession' => false,
                'loginUrl'      => null,

            ],
            'request'                    => [
                'class'                => Request::class,
                'enableCsrfValidation' => false,
                'enableCsrfCookie'     => false,
                'trustedHosts'         => [
                    '10.0.0.0/8',
                    '172.0.0.0/8',
                ],
                // To enable rendering in tests.
                'scriptFile'           => realpath(
                    __DIR__.'/../../public/index.php'
                ),
                'scriptUrl'            => '/',
                'parsers'              => [
                    'application/json' => yii\web\JsonParser::class,
                ],
            ],
            'response'                   => [
                'class'      => Response::class,
                'formatters' => [
                    Response::FORMAT_JSON => [
                        'class'       => JsonResponseFormatter::class,
                        'prettyPrint' => true,
                    ],
                ],
            ],
            'db'                         => Connection::class,
            'preloadingSourceRepository' => PreloadingSourceRepository::class,
            'abacManager'                => static function (
                Resolver $resolver, // Taken from container
                RuleEngine $engine, // Taken from container
                PermissionRepository $preloadingSourceRepository  // Taken from app
            ) {
                $environment = new class() extends ArrayObject
                    implements Environment {
                };
                $environment['globalAuthorizable'] = new Authorizable(
                    AccessChecker::GLOBAL, AccessChecker::BUILTIN
                );
                return new AuthManager(
                    $engine,
                    $preloadingSourceRepository,
                    $resolver,
                    $environment
                );
            },
            'authManager'                => static fn(AuthManager $abacManager
            ) => new \herams\common\components\AuthManager(
                $abacManager,
                User::class
            ),
            'cache'                      => [
                'class' => CacheInterface::class,
            ],
            //        'formatter' => [
            //            'numberFormatterOptions' => [
            //                NumberFormatter::MIN_FRACTION_DIGITS => 0,
            //                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            //            ]
            //
            //        ],
            'log'                        => [
                'flushInterval' => 1,
                'traceLevel'    => YII_DEBUG ? 3 : 0,
                'targets'       => [
                    [
                        'exportInterval' => 1,
                        'class'          => FileTarget::class,
                        'levels'         => ['error', 'warning'],
                        'logFile'        => '@runtime/logs/error.log',
                    ],
                ],
            ],
            'i18n'                       => [
                'class'        => I18N::class,
                'translations' => [
                    'app*' => [
                        'class'                                   => GettextMessageSource::class,
                        'useMoFile'                               => false,
                        'basePath'                                => '@vendor/herams/i18n/locales',
                        'catalog'                                 => 'LC_MESSAGES/app',
                        'on '
                        .MessageSource::EVENT_MISSING_TRANSLATION => static function (
                            MissingTranslationEvent $event
                        ) {
                            if (YII_DEBUG) {
                                $event->translatedMessage
                                    = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
                            }
                        },
                    ],
                    'yii*' => [
                        'class'                                   => ExtendedGettextMessageSource::class,
                        'useMoFile'                               => false,
                        'basePath'                                => '@vendor/herams/i18n/locales',
                        'catalog'                                 => 'LC_MESSAGES/app',
                        'on '
                        .MessageSource::EVENT_MISSING_TRANSLATION => static function (
                            MissingTranslationEvent $event
                        ) {
                            if (YII_DEBUG) {
                                $event->translatedMessage
                                    = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
                            }
                        },
                    ],
                ],
            ],
            'translator'                 => [
                'class' => '\herams\common\components\TranslationComponent',
            ],
        ],
        'controllerNamespace' => 'herams\\api\\controllers',
        'defaultRoute'        => 'health/status',
    ];

    if (YII_DEBUG) {
        $config = ArrayHelper::merge(
            $config,
            include(__DIR__.'/debug.php')
        );
    }

    $container->set(Application::class, $config);
};
