<?php

namespace prime\objects;

class Deferred
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
}