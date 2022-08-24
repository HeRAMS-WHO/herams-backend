<?php

declare(strict_types=1);

namespace prime\objects;

use prime\interfaces\BreadcrumbInterface;
use Traversable;

class BreadcrumbCollection implements \IteratorAggregate
{
    /**
     * @var list<BreadcrumbInterface>
     */
    private array $values = [];

    public function add(BreadcrumbInterface ...$values): self
    {
        foreach ($values as $value) {
            $this->values[] = $value;
        }
        return $this;
    }

    public function mergeWith(BreadcrumbCollection $collection): void
    {
        $this->values = [...$this->values, ...$collection->values];
    }

    /**
     * @return Traversable<BreadcrumbInterface>
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->values);
    }
}
