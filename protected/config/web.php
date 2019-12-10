<?php
/** @var \prime\components\Environment $env */

use prime\components\NotificationService;
use yii\web\AssetConverter;

$config = yii\helpers\ArrayHelper::merge(require(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\controllers',
    'bootstrap' => [
        'notificationService',
    ],
    'defaultRoute' => 'marketplace/herams',
    'components' => [
        'notificationService' => [
            'class' => NotificationService::class
        ],
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'cache' => false,
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view'
                ],
                [
                    'pattern' => '<controller>/<id:\d+>/<action:[\w-]+>',
                    'route' => '<controller>/<action>'
                ],
                [
                    'pattern' => '<controller>/<action:\w+>',
                    'route' => '<controller>/<action>'
                ],
                // For testing.
                [
                    'pattern' => '/',
                    'route' => 'site/world-map'
                ],
                \prime\modules\Api\Module::urlRule()

            ]
        ],
        'request' => [
            'class' => \yii\web\Request::class,
            'trustedHosts' => [
                '10.42.0.0/16'
            ],
            'cookieValidationKey' => 'blasdf9832h238iwe',
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
                \yii\web\JqueryAsset::class => [
                    'sourcePath' => '@bower/jquery/dist',
                    'js' => [
                        (YII_DEBUG) ? 'jquery.js' : 'jquery.min.js',
                    ]
                ]
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