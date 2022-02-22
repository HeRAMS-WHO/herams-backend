<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\interfaces\EventDispatcherInterface;
use yii\base\Event;

use function iter\chain;
use function iter\flatten;

/**
 *
 */
class EventDispatcher implements EventDispatcherInterface
{
    private array $handlers = [];
    public function on(string $class, string $name, \Closure $handler, $data = null, bool $append = true): void
    {
        $key = "{$class}#{$name}";
        if (!isset($this->handlers[$key])) {
            $this->handlers[$key] = [];
        }
        if ($append) {
            $this->handlers[$key][] = [$handler, $data];
        } else {
            $this->handlers[$key] = [[$handler, $data], ...$this->handlers[$key]];
        }
    }

    public function off(string $class, string $name, \Closure $handler): void
    {
        $key = "{$class}#{$name}";
        foreach ($this->handlers[$key] ?? [] as $i => [$registeredHandler, $data]) {
            if ($handler === $registeredHandler) {
                unset($this->handlers[$key][$i]);
            }
        }
    }

    public function trigger(string $class, string $name, Event $event): void
    {
        $event->handled = false;
        $event->name = $name;
        foreach (chain([$class], class_parents($class), class_implements($class)) as $prefix) {
            $key = "{$prefix}#{$name}";

            foreach ($this->handlers[$key] ?? [] as [$handler, $data]) {
                $event->data = $data;
                $handler($event);
                if ($event->handled) {
                    return;
                }
            }
        }
    }
}
