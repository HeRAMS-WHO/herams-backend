<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use PhpParser\Error;

interface JsonFieldInterface {
    /**
     * @param array|string|object $value
     * @throws Error if passed value is not a valid JSON
     * @psalm-assert void
     */
    public function __construct(array|string|object $value);

    /**
     * @return array|object
     */
    public function getValue(): array|object;

    /**
     * @return string
     */
    public function __toString(): string;

}