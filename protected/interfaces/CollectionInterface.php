<?php

namespace prime\interfaces;

interface CollectionInterface extends \Traversable
{
    /**
     * @return int The number of items in this collection.
     */
    public function size();

    /**
     * @param mixed $key
     * @return object
     */
    public function get($key);

    /**
     * @param mixed $key
     * @param object $item
     */
    public function add($key, $item);

    public function append($item);

    public function filter(\Closure $closure);

    public function sort(\Closure $closure);
}
