<?php

namespace prime\objects;

use prime\interfaces\CollectionInterface;
use Traversable;

class Collection implements \IteratorAggregate, CollectionInterface, \ArrayAccess, \Countable
{
    /**
     * @var string The type of objects allowed in this collection.
     */
    protected $dataType;
    protected $data = [];

    public function __construct($items = [], $keepKeys = false)
    {
        foreach ($items as $key => $item) {
            if ($keepKeys) {
                $this->add($key, $item);
            } else {
                $this->append($item);
            }
        }
    }

    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return json_encode($this->data);
    }

    /**
     * @return int The number of Responses in this collection.
     */
    public function size()
    {
        return count($this->data);
    }

    /**
     * @param mixed $index
     * @return mixed
     */
    public function get($index)
    {
        return $this->data[$index];
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed$value): void
    {
        $this->add($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function add($key, $item): void
    {
        $this->validate($item);
        $this->data[$key] = $item;
    }

    public function append($item): void
    {
        $this->validate($item);
        $this->data[] = $item;
    }

    protected function validate($item): void
    {
        if (isset($this->dataType) && !is_subclass_of($item, $this->dataType)) {
            $type = is_object($item) ? get_class($item) : gettype($item);
            throw new \DomainException("Expected {$this->dataType} got $type");
        }
    }

    /**
     * Filters the collection.
     *
     * @param \Closure $closure A closure with arguments $value and $key.
     * @return Collection A collection containing the filtered results.
     */
    public function filter(\Closure $closure): self
    {
        $result = clone $this;
        $result->data = array_filter($result->data, $closure, ARRAY_FILTER_USE_BOTH);
        return $result;
    }

    /**
     * Sorts the collection, returns a new collection.
     * @param \Closure $closure
     * @return Collection
     */
    public function sort(\Closure $closure): self
    {
        $result = clone $this;
        usort($result->data, $closure);
        return $result;
    }
}
