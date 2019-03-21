<?php


namespace prime\components;


use ArrayAccess;

class Environment implements ArrayAccess {
    private $data = [];

    public function __construct(string $file)
    {
        $this->data = getenv();
        if (file_exists($file)) {
            $this->data = array_merge($this->data, json_decode(file_get_contents($file), true));
        }
    }

    public function get($name, $default = null)
    {
        return $this->offsetGet($name) ?? $default;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? getenv($offset) ?: null;
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception('Not supported');
    }

    public function offsetUnset($offset)
    {
        throw new \Exception('Not supported');
    }

    public function __debugInfo()
    {
        return ['data' => '** MASKED **'];
    }

}