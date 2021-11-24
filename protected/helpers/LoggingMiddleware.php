<?php

declare(strict_types=1);

namespace prime\helpers;

use League\Tactician\Middleware;

class LoggingMiddleware implements Middleware
{
    public function execute($command, callable $next)
    {
        $class = get_class($command);
        \Yii::info("Executing command of type {$class}", "commandbus");
        $next($command);
    }
}
