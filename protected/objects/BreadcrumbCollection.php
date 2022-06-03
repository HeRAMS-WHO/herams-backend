<?php

declare(strict_types=1);

namespace prime\objects;

use prime\interfaces\BreadcrumbInterface;

class BreadcrumbCollection implements \Iterator
{
    private array $values = [];

    private int $position = 0;

    public function __construct(array $values = [])
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public function add(BreadcrumbInterface $value, ?int $index = null): self
    {
        if (is_null($index)) {
            $this->values[] = $value;
        } else {
            $this->values[$index] = $value;
        }

        return $this;
    }

    public function current(): BreadcrumbInterface
    {
        return $this->values[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->values[$this->position]);
    }
}
