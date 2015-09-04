<?php
$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
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
        ]
    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}
if (defined('YII_ENV') && file_exists(__DIR__ . '/' . YII_ENV . '.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/' . YII_ENV . '.php'));
}
return $config;
