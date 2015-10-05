<?php
$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\controllers',
    'components' => [
        'user' => [
            'identityClass' => \prime\models\User::class
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],
        'request' => [
            'cookieValidationKey' => 'ag;haew;ugaihtuaet;erk;agjewhghufrai;c,avmbnt8s;ge9facwmierg;o9aut,mgs95ue;l6u5d',
            'class' => \app\components\Request::class
        ],
        'assetManager' => [
            'class' => \yii\web\AssetManager::class,
            // http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#cache-busting
            'appendTimestamp' => true,
            'converter' => [
                'class' => \yii\web\AssetConverter::class,
                'commands' => [
                    'sass' => ['css', 'sass {from} {to}'],
                    'scss' => ['css', 'sass {from} {to}'],
                ]
                // Yii::getAlias not yet available.
//                'destinationDir' => $compiledAssetDir,
            ],
            // Override bootstrap
            'bundles' => [
                \yii\bootstrap\BootstrapAsset::class => [
                    'css' => []
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
        ],
    ],
    'modules' => [
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
if (defined('YII_ENV') && file_exists(__DIR__ . '/envs/' . YII_ENV . '.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/envs/' . YII_ENV . '.php'));
}

if (file_exists(__DIR__ . '/local.php')) {
    $config = yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/local.php'));
}

return $config;
