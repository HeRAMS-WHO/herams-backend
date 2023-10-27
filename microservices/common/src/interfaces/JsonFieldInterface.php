<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use PhpParser\Error;

interface JsonFieldInterface
{
    /**
     * @throws Error if passed value is not a valid JSON
     * @psalm-assert void
     */
    public function __construct(array|string|object $value);

    public function getValue(): array|object;

    public function __toString(): string;
}
