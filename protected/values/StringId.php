<?php

declare(strict_types=1);

namespace prime\values;

class StringId extends Id
{
    public function __construct(private string $id)
    {
    }

    public function getValue(): string
    {
        return $this->id;
    }
}
