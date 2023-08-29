<?php

declare(strict_types=1);

namespace herams\common\components;

use herams\common\interfaces\CreateUrlInterface;
use yii\di\Container;
use yii\di\Instance;
use yii\web\UrlManager;

class LazyUrlFactory implements CreateUrlInterface
{
    private UrlManager $manager;

    public function __construct(
        private Instance $instance,
        private Container $container,
        private readonly string|null $prefix = null
    ) {
    }

    public function createUrl(string $route, array $params): string
    {
        if (! isset($this->manager)) {
            $this->manager = Instance::ensure($this->instance, UrlManager::class, $this->container);
            $this->manager->setBaseUrl($this->prefix);
            unset($this->instance, $this->container);
        }

        return $this->manager->createAbsoluteUrl([$route, ...$params]);
    }
}
