<?php
declare(strict_types=1);

namespace prime\values;

use Attribute;

#[Attribute]
class InvalidPoint extends Point
{
    public function __construct(private string $value)
    {
        parent::__construct(null, 0, 0);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
