<?php

declare(strict_types=1);

namespace prime\components;

use prime\interfaces\EnvironmentInterface;

class Secret implements \Stringable
{
    private string $name;

    private EnvironmentInterface $environment;

    public function __construct(EnvironmentInterface $environment, string $name)
    {
        $this->environment = $environment;
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->environment->getSecret($this->name);
    }
}
