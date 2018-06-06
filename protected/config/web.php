<?php
$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\controllers',
    'defaultRoute' => 'site',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => ['api/surveys', 'api/collections', 'api/maps', 'api/countries',
                        'api/coordinates', 'api/categories', 'api/charts', 'api/filters', 'api/locations'],
                    'tokens' => [
                        '{id}' => '<id:\\w[\\w,]*>'
                    ]
                ],
                [
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/read'
                ],
            ]
        ],
        'request' => [
            'cookieValidationKey' => 'blasdf9832h238iwe',
            'class' => \app\components\Request::class,
            // To enable rendering in tests.
            'scriptFile' => realpath(__DIR__ . '/../../public/index.php'),
            'scriptUrl' => '/'
        ],
        'assetManager' => [
            'class' => \yii\web\AssetManager::class,
            // http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#cache-busting
            'appendTimestamp' => true,
            'converter' => [
                'class' => \yii\web\AssetConverter::class,
                'commands' => [
                    'sass' => ['css', 'sassc {from} {to}'],
                    'scss' => ['css', 'sassc {from} {to}'],
                ]
                // Yii::getAlias not yet available.
//                'destinationDir' => $compiledAssetDir,
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
                    '@dektrium/user/views' => '@app/views/users',

                ]
            ],
        ]
    ],
    'modules' => [
        'api' => [
            'class' => \prime\api\Api::class
        ],
        'user' => [
            'controllerMap' => [
                'registration' => \prime\controllers\RegistrationController::class,
                'recovery' => \prime\controllers\RecoveryController::class
            ]
        ],
        'gridview' => [
            'class' => \kartik\grid\Module::class
        ]
    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}
$config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/envs/' . YII_ENV . '.php'));

if (file_exists(__DIR__ . '/local.php') && YII_ENV !== 'codeception') {
    $config = yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/local.php'));
}

return $config;