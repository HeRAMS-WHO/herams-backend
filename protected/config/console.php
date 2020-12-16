<?php
declare(strict_types=1);

/** @var \prime\components\KubernetesSecretEnvironment $env */

$config = yii\helpers\ArrayHelper::merge(include(__DIR__ . '/common.php'), [
    'controllerNamespace' => 'prime\\commands',
    'controllerMap' => [
        'cache' => \prime\commands\CacheController::class,
    ],
    'aliases' => [
        // Mainly for console apps.
        '@web' => '/',
        '@webroot' => realpath(__DIR__ . '/../../public')
    ],
    'components' => [
        // In console mode never read from the LS cache, this forces the data to be refreshed.
        'limesurveyCache' => function () {
            $result = new class([
                'cachePath' => '@runtime/limesurveyCache'
            ]) extends \yii\caching\FileCache {
                protected function getValue($key)
                {
                    return false;
                }
            };
            return $result;
        }
    ]
]);

if (YII_DEBUG && file_exists(__DIR__ . '/debug.php')) {
    $config = \yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/debug.php'));
}
if (file_exists(__DIR__ . '/local.php')) {
    $config = yii\helpers\ArrayHelper::merge($config, include(__DIR__ . '/local.php'));
}
return $config;
