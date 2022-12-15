<?php

declare(strict_types=1);

/** @var KubernetesSecretEnvironment $env */

use herams\common\helpers\KubernetesSecretEnvironment;
use herams\console\controllers\CacheController;
use herams\console\controllers\MigrateController;

$commonDiConfigurator = new \herams\common\config\CommonConfigurator();
$commonDiConfigurator->configure($env, \Yii::$container);
\Yii::$container->set(\herams\common\interfaces\AccessCheckInterface::class, \prime\helpers\JobAccessCheck::class);

$config = [
    'id' => 'herams-console',
    'name' => 'HeRAMS Console',
    'basePath' => realpath(__DIR__ . '/../'),
    'aliases' => [
        '@herams/console' => '@app',

    ],
    'controllerNamespace' => 'herams\\console\\controllers',
    'controllerMap' => [
        'cache' => CacheController::class,
        'migrate' => [
            'class' => MigrateController::class,
            'migrationNamespaces' => [
                'herams\\console\\migrations'
            ],
            'migrationPath' => null
        ]
    ],
    'components' => [
        'db' => \yii\db\Connection::class,
    ]
];

return $config;
