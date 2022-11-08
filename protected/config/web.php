<?php

declare(strict_types=1);

/**
 * @var \herams\common\helpers\KubernetesSecretEnvironment $env
 */

use Carbon\Carbon;
use herams\common\components\Formatter;
use kartik\dialog\DialogAsset;
use kartik\dialog\DialogBootstrapAsset;
use prime\components\ApiRewriteRule;
use prime\components\JobSubmissionService;
use prime\components\LanguageSelector;
use prime\components\MaintenanceMode;
use prime\components\NotificationService;
use yii\di\Instance;
use yii\web\DbSession;
use yii\web\UrlManager;
use yii\widgets\PjaxAsset;

$commonDiConfigurator = new \herams\common\config\CommonConfigurator();
$commonDiConfigurator->configure($env, \Yii::$container);
$config = yii\helpers\ArrayHelper::merge(require(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\controllers',
    'bootstrap' => [
        MaintenanceMode::class,
        JobSubmissionService::class,
        'notificationService',
        'languageSelector',

    ],
    'defaultRoute' => 'marketplace/herams',
    'components' => [
        'user' => [
            'class' => \yii\web\User::class,
            'loginUrl' => '/session/create',
            'identityClass' => \herams\common\domain\user\User::class,
            'on ' . \yii\web\User::EVENT_AFTER_LOGIN => function (\yii\web\UserEvent $event) {
                if (! empty($event->identity->language)) {
                    \Yii::$app->language = $event->identity->language;
                }
            },
        ],
        'jobQueue' => \JCIT\jobqueue\interfaces\JobQueueInterface::class,
        'formatter' => [
            'class' => Formatter::class,
        ],
        'session' => [
            'class' => DbSession::class,
            'timeout' => 12 * 3600,
            'cookieParams' => [
                'httponly' => 'true',
                'samesite' => 'strict'
            ],
            'readCallback' => static function (array $fields): array {
                return [
                    '__id' => $fields['user_id'] ?? null,
                ];
            },
            'writeCallback' => static function (DbSession $session): array {
                $fields = [
                    'user_id' => $session->get('__id'),
                    'created_at' => $session->get('created', Carbon::now()),
                    'updated_at' => Carbon::now(),
                    // Workaround for https://github.com/yiisoft/yii2/issues/19130
                    'expire' => time() + $session->getTimeout(),
                ];
                $session->remove('__id');
                return $fields;
            },
        ],
        'languageSelector' => [
            'class' => LanguageSelector::class,
        ],
        'notificationService' => [
            'class' => NotificationService::class,
        ],
        'view' => \prime\components\View::class,
        'urlManager' => [
            'class' => UrlManager::class,
            'cache' => false,
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '/api-proxy/<api:[\w-]+>/<sub:.*>',
                    'route' => '/api-proxy/<api>'
                ],
                [
                    'class' => ApiRewriteRule::class,
                    '__construct()' => [
                        Instance::ensure('apiUrlManager', UrlManager::class)
                    ]
                ],
                [
                    'pattern' => '<controller>',
                    'route' => '<controller>',
                ],
                [
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view',
                ],
                [
                    'pattern' => '<controller>/<id:[\w-]+>/<action:[\w-]+>',
                    'route' => '<controller>/<action>',
                ],
                [
                    'pattern' => '<controller>/<action:[\w-]+>',
                    'route' => '<controller>/<action>',
                ],
                // For testing.
                [
                    'pattern' => '/',
                    'route' => 'site/world-map',
                ],


            ],
        ],
        'request' => [
            'class' => \yii\web\Request::class,
            'csrfCookie' => [
                'httpOnly' => true,
                'sameSite' => \yii\web\Cookie::SAME_SITE_STRICT
            ],
            'trustedHosts' => [
                '10.0.0.0/8',
                '172.0.0.0/8',
            ],
            'cookieValidationKey' => $env->getWrappedSecret('app/cookie_validation_key'),
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
        'assetManager' => [
            'class' => \yii\web\AssetManager::class,
            // http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#cache-busting
            'appendTimestamp' => true,
            'forceCopy' => YII_DEBUG,
            'bundles' => [
                // Override bootstrap
                \yii\bootstrap\BootstrapPluginAsset::class => [
                    'baseUrl' => '@npm/bootstrap/dist',
                    'sourcePath' => null,
                ],
                DialogAsset::class => false,
                DialogBootstrapAsset::class => false,
                //                \yii\bootstrap\BootstrapThemeAsset::class => false
                \yii\bootstrap\BootstrapAsset::class => [
                    'baseUrl' => '@npm/bootstrap/dist',
                    'sourcePath' => null,
                    'css' => [
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
    ],
    'modules' => [
        'gridview' => [
            'class' => \kartik\grid\Module::class,
            'controllerMap' => [],
        ],
    ],
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}

return $config;
