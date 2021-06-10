<?php
declare(strict_types=1);

namespace prime\values;

class IntegerId extends Id
{
    public function __construct(private int $id)
    {
    }

    public function getValue(): int
    {
        return $this->id;
    }


    public function __toString()
    {
        return (string) $this->id;
    }

    public function jsonSerialize()
    {
        return $this->id;
    }
}
