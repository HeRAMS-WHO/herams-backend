<?php
declare(strict_types=1);

namespace prime\components;

use yii\base\InvalidConfigException;

class Environment
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

    public function get(string $name, $default = null)
    {
        return $this->data[$name] ?? getenv($name) ?: $default;
    }

    public function getSecret(string $name)
    {
        if (!isset($this->secretCache[$name])) {
            $secretFile = "{$this->secretDir}/{$name}";
            if (!file_exists($secretFile)) {
                throw new InvalidConfigException("Missing value for secret $name");
            }
            $this->secretCache[$name] = file_get_contents($secretFile);
        }
        return $this->secretCache[$name];
    }
}
