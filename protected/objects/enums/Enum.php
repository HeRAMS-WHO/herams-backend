<?php
declare(strict_types=1);

namespace prime\objects\enums;

abstract class Enum extends \Spatie\Enum\Enum
{
    final protected function __construct(int|string $value)
    {
        parent::__construct($value);
    }
}
