<?php

declare(strict_types=1);

/** @var KubernetesSecretEnvironment $env */

use herams\common\helpers\KubernetesSecretEnvironment;
use herams\common\interfaces\EnvironmentInterface;
use herams\console\controllers\CacheController;
use herams\console\controllers\MigrateController;

return function (EnvironmentInterface $env, \yii\di\Container $container): void {
    $commonDiConfigurator = new \herams\common\config\CommonConfigurator();
    $commonDiConfigurator->configure($env, \Yii::$container);
    $diConfigurator = require __DIR__ . '/di.php';
    $diConfigurator($env, $container);
    $config = [
        'id' => 'herams-console',
        'name' => 'HeRAMS Console',
        'basePath' => realpath(__DIR__ . '/../'),
        'aliases' => [
            '@herams/console' => '@app',
            '@root' => '@app/../../../',
            '@tests' => '@root/tests',
        ],
        'controllerNamespace' => 'herams\\console\\controllers',
        'controllerMap' => [
            'cache' => CacheController::class,
            'migrate' => [
                'class' => MigrateController::class,
                'migrationNamespaces' => [
                    'herams\\console\\migrations',
                ],
                'migrationPath' => null,
            ],
        ],
        'components' => [
            'db' => \yii\db\Connection::class,
        ],
    ];

    $container->set(\yii\console\Application::class, $config);
};
