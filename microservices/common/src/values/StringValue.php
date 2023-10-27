<?php

declare(strict_types=1);

namespace herams\common\values;

abstract class StringValue
{
    private string $value;

    public function __construct(string|int $value)
    {
        $this->value = (string)$value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
