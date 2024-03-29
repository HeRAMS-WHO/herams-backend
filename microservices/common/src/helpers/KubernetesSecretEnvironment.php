<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\interfaces\EnvironmentInterface;
use yii\base\InvalidConfigException;

class KubernetesSecretEnvironment implements EnvironmentInterface
{
    private array $data = [];

    private string $secretDir;

    private array $secretCache = [];

    public function __construct(string $secretDir, string ...$files)
    {
        $this->data = getenv();
        foreach ($files as $file) {
            if (file_exists($file)) {
                $this->data = array_merge($this->data, json_decode(file_get_contents($file), true));
            }
        }

        $this->secretDir = $secretDir;
    }

    public function __debugInfo()
    {
        return [];
    }

    public function get(string $name): string
    {
        return $this->data[$name];
    }

    public function getSecret(string $name): string
    {
        if (! isset($this->secretCache[$name])) {
            $secretFile = "{$this->secretDir}/{$name}";
            if (! file_exists($secretFile)) {
                throw new InvalidConfigException("Missing value for secret $name");
            }
            if (false === $secret = file_get_contents($secretFile)) {
                throw new InvalidConfigException("Couldn't read secret $name");
            }
            $this->secretCache[$name] = trim($secret);
        }
        return $this->secretCache[$name];
    }

    public function getWrappedSecret($name): Secret
    {
        return new Secret($this, $name);
    }

    public function getWithDefault(string $name, string $default): string
    {
        if (isset($this->data[$name]) && $this->data[$name] !== "") {
            return $this->data[$name];
        }
        return $default;
    }
}
