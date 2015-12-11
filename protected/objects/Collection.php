<?php

namespace prime\objects;

use prime\interfaces\CollectionInterface;
use Traversable;
class Collection implements \IteratorAggregate, CollectionInterface, \ArrayAccess
{
    /**
     * @var string The type of objects allowed in this collection.
     */
    protected $dataType;
    protected $data = [];

    public function __construct($items = [], $keepKeys = false)
    {
        foreach($items as $key => $item) {
            if($keepKeys) {
                $this->add($key, $item);
            } else {
                $this->append($item);
            }
        }
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

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function add($key, $item)
    {
        $this->validate($item);
        $this->data[$key] = $item;
    }

    public function append($item) {
        $this->validate($item);
        $this->data[] = $item;
    }

    protected function validate($item)
    {
        if (isset($this->dataType) && !is_subclass_of($item, $this->dataType)) {
            $type = is_object($item) ? get_class($item) : gettype($item);
            throw new \DomainException("Expected {$this->dataType} got $type");
        }
    }
}