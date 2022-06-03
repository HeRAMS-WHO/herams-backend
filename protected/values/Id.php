<?php

declare(strict_types=1);

namespace prime\values;

abstract class Id implements \Stringable, \JsonSerializable
{
    abstract public function getValue(): int|string;

    public function __toString()
    {
        return (string) $this->getValue();
    }

    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
