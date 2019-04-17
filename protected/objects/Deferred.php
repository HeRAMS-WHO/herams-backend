<?php

namespace prime\objects;

class Deferred implements \Serializable
{
    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    protected function resolve()
    {
        return call_user_func($this->closure);
    }

    public function __toString()
    {
        return $this->resolve();
    }


    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return $this->resolve();
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->closure = function() use ($serialized) {
            return $serialized;
        };
    }
}