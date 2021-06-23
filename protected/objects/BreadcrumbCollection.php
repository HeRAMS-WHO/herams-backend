<?php
declare(strict_types=1);

namespace prime\objects;

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

    public function add(Breadcrumb $value, ?int $index = null): self
    {
        if (is_null($index)) {
            $this->values[] = $value;
        } else {
            $this->values[$index] = $value;
        }

        return $this;
    }

    public function current(): Breadcrumb
    {
        return $this->values[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): self
    {
        $this->position++;
        return $this;
    }

    public function rewind(): self
    {
        $this->position = 0;
        return $this;
    }

    public function valid(): bool
    {
        return isset($this->values[$this->position]);
    }
}
