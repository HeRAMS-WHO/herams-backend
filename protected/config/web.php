<?php
$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],
        'request' => [
            'cookieValidationKey' => 'blasdf9832h238iwe',
            'class' => \app\components\Request::class
        ],
        'assetManager' => [
            // http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#cache-busting
            'appendTimestamp' => true,
            'converter' => [
                'class' => \nizsheanez\assetConverter\Converter::class,

                'parsers' => [
                    'scss' => []
                ]
            ]
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/users'
                ]
            ]
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
