<?php

declare(strict_types=1);

namespace prime\components;

use prime\interfaces\EnvironmentInterface;
use yii\base\InvalidConfigException;

class InsecureSecretEnvironment implements EnvironmentInterface
{
    private array $data = [];

    public function __construct(string ...$files)
    {
        $this->data = getenv();
        foreach ($files as $file) {
            if (file_exists($file)) {
                $this->data = array_merge($this->data, json_decode(file_get_contents($file), true));
            }
        }
    }

    public function get(string $name, $default = null)
    {
        return $this->data[$name] ?? getenv($name) ?: $default;
    }

    public function getSecret(string $name): string
    {
        if (! isset($this->data["SECRET_$name"])) {
            throw new InvalidConfigException("Missing variable SECRET_$name");
        }
        return trim($this->data["SECRET_$name"]);
    }

    public function getWrappedSecret($name): Secret
    {
        return new Secret($this, $name);
    }
}
