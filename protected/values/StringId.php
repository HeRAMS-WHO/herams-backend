<?php

declare(strict_types=1);

namespace prime\values;

class StringId extends Id
{
    private string $id;
    public function __construct(string|int $id)
    {
        $this->id = (string) $id;
    }

    public function getValue(): string
    {
        return $this->id;
    }
}
