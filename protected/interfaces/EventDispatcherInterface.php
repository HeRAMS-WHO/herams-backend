<?php

declare(strict_types=1);

namespace prime\interfaces;

use yii\base\Event;

interface EventDispatcherInterface
{
    public function on(string $class, string $name, \Closure $handler, $data = null, bool $append = true): void;

    public function off(string $class, string $name, \Closure $handler): void;

    public function trigger(string $class, string $name, Event $event): void;
}
