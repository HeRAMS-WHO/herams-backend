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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => \yii\web\GroupUrlRule::class,
                    'prefix' => 'api',
                    'rules' => [
                        [
                            'class' => \yii\rest\UrlRule::class,
                            'controller' => ['api/surveys', 'api/collections', 'api/maps', 'api/countries',
                                'api/coordinates', 'api/categories', 'api/charts', 'api/filters', 'api/locations'],
                            'tokens' => [
                                '{id}' => '<id:\\w[\\w,]*>'
                            ]
                        ],
                    ]
                ],
//                [
//                    'class' => \yii\web\GroupUrlRule::class,
//                    'prefix' => 'v2',
//                    'rules' => [
//                        [
//                            'pattern' => '<controller:\w+>/<id:\d+>/<action:\w+>',
//                            'route' => '<controller>/<action>'
//                        ]
//
//                    ]
//                ],
                [
                    'class' => \yii\rest\UrlRule::class,
//                    'only' => [
//                        'view',
//                        'index'
//                    ],

                    'extraPatterns' => [
                        'GET {id}/<action:\w+>' => '<action>'
                    ],
                    'controller' => [
                        'v2/project',
                        'v2/workspace',
                        'v2/facility'
                    ],
                ],
                [
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view'
                ],
                [
                    'pattern' => '<controller>/<id:\d+>/<action:[\w-]+>',
                    'route' => '<controller>/<action>'
                ],
                // For testing.
                [
                    'pattern' => '/',
                    'route' => 'site/world-map',
                    'defaults' => [
                        'id' => 1
                    ]
                ]

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
            'converter' => [
                'class' => AssetConverter::class,
                'commands' => [
                    'sass' => ['css', 'sassc {from} {to}'],
                    'scss' => ['css', 'sassc {from} {to}'],
                ]
            ],
            'bundles' => [
                // Override bootstrap
                \yii\bootstrap\BootstrapAsset::class => [
                    'css' => []
                ],
                //TODO remove if Yii issue 10973 is fixed
                \yii\web\JqueryAsset::class => [
                    'sourcePath' => '@bower/jquery/dist',
                    'js' => [
                        (YII_DEBUG) ? 'jquery.js' : 'jquery.min.js',
                    ]
                ]
            ]
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views/mail' => '@app/mail',
                    '@dektrium/user/views' => '@app/views/user',
                ]
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/site/error'
        ]
    ],
    'modules' => [
        'api' => [
            'class' => \prime\api\v1\Api::class
        ],
        'v2' => [
            'class' => \prime\api\v2\Module::class
        ],
        'gridview' => [
            'class' => \kartik\grid\Module::class
        ]
    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}

return $config;