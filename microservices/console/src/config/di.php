<?php

declare(strict_types=1);

use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\EnvironmentInterface;
use herams\console\helpers\DisabledAccessCheck;
use yii\di\Container;

return function (EnvironmentInterface $env, Container $container): void {
    $container->set(AccessCheckInterface::class, DisabledAccessCheck::class);
};
