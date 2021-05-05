<?php
declare(strict_types=1);

namespace prime\objects\enums;

abstract class Enum extends \Spatie\Enum\Enum
{
    final protected function __construct(int|string $value)
    {
        parent::__construct($value);
    }

    final public static function from(int|string $value): static
    {
        return new static($value);
    }

    final public static function tryFrom(int|string $value): null|static
    {
        try {
            return new static($value);
        } catch (\BadMethodCallException) {
            return null;
        }
    }
}
