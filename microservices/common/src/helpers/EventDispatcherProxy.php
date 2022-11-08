<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\interfaces\EventDispatcherInterface;
use yii\base\Event;

/**
 * This is a proxy for Yii's global static events.
 */
class EventDispatcherProxy implements EventDispatcherInterface
{
    public function on(string $class, string $name, \Closure $handler, $data = null, bool $append = true): void
    {
        Event::on($class, $name, $handler, $data, $append);
    }

    public function off(string $class, string $name, \Closure $handler): void
    {
        Event::off($class, $name, $handler);
    }

    public function trigger(object|string $class, string $name, null|Event $event = null): void
    {
        Event::trigger($class, $name, $event);
    }
}
