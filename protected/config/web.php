<?php
/** @var \prime\components\KubernetesSecretEnvironment $env */

use Carbon\Carbon;
use prime\components\LanguageSelector;
use prime\components\NotificationService;
use yii\web\DbSession;

$config = yii\helpers\ArrayHelper::merge(require(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\controllers',
    'aliases' => [
        '@npm' => '@vendor/../node_modules',

    ],
    'bootstrap' => [
        'notificationService',
        'languageSelector'
    ],
    'defaultRoute' => 'marketplace/herams',
    'components' => [
        'session' => [
            'class' => DbSession::class,
            'readCallback' => static function (array $fields): array {
                return [
                    '__id' => $fields['user_id'] ?? null,
                ];
            },
            'writeCallback' => static function (DbSession $session): array {
                $fields = [
                    'user_id' => $session->get('__id'),
                    'created' => $session->get('created', Carbon::now()),
                    'updated' => Carbon::now(),
                ];
                $session->remove('__id');
                return $fields;
            }
        ],
        'languageSelector' => [
            'class' => LanguageSelector::class
        ],
        'notificationService' => [
            'class' => NotificationService::class
        ],
        'view' => \prime\components\View::class,
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'cache' => false,
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                \prime\modules\Api\Module::urlRule(),
                [
                    'pattern' => '<controller>',
                    'route' => '<controller>'
                ],
                [
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view'
                ],
                [
                    'pattern' => '<controller>/<id:\d+>/<action:[\w-]+>',
                    'route' => '<controller>/<action>'
                ],
                [
                    'pattern' => '<controller>/<action:[\w-]+>',
                    'route' => '<controller>/<action>'
                ],
                // For testing.
                [
                    'pattern' => '/',
                    'route' => 'site/world-map'
                ],


            ]
        ],
        'request' => [
            'class' => \yii\web\Request::class,
            'trustedHosts' => [
                '10.42.0.0/16'
            ],
            'cookieValidationKey' => $env->getWrappedSecret('app/cookie_validation_key'),
            // To enable rendering in tests.
            'scriptFile' => realpath(__DIR__ . '/../../public/index.php'),
            'scriptUrl' => '/',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ]
        ],
        'response' => [
            'class' => \yii\web\Response::class,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => \yii\web\JsonResponseFormatter::class,
                    'prettyPrint' => true
                ]
            ]
        ],
        'assetManager' => [
            'class' => \yii\web\AssetManager::class,
            // http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#cache-busting
            'appendTimestamp' => true,
            'forceCopy' =>  YII_DEBUG,
            'bundles' => [
                // Override bootstrap
                \yii\bootstrap\BootstrapAsset::class => [
                    'css' => []
                ],
            ]
        ],
        'errorHandler' => [
            'errorAction' => '/site/error'
        ]
    ],
    'modules' => [
        'gridview' => [
            'class' => \kartik\grid\Module::class
        ]
    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}

return $config;
