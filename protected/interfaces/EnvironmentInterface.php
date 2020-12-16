<?php
declare(strict_types=1);

namespace prime\interfaces;


use prime\components\Secret;

interface EnvironmentInterface
{

    public function get(string $name, $default = null);
    public function getSecret(string $name): string;
    public function getWrappedSecret(string $name): Secret;
}