<?php
declare(strict_types=1);

namespace prime\components;


class Environment {
    private $data = [];

    public function __construct(?string $file = null)
    {
        $this->data = getenv();
        if (isset($file) && file_exists($file)) {
            $this->data = array_merge($this->data, json_decode(file_get_contents($file), true));
        }
    }

    public function get(string $name, $default = null)
    {
        return $this->data[$name] ?? getenv($name) ?: $default;
    }
}