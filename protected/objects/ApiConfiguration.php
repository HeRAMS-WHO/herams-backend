<?php

declare(strict_types=1);

namespace prime\objects;

final class ApiConfiguration
{
    public function __construct(
        public readonly string $host
    ) {
    }
}
