<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use yii\di\Container;

interface ContainerConfiguratorInterface
{
    public function configure(EnvironmentInterface $environment, Container $container): void;
}
