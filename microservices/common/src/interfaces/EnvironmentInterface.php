<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\helpers\Secret;

interface EnvironmentInterface
{
    public function get(string $name, $default = null);

    public function getSecret(string $name): string;

    public function getWrappedSecret(string $name): Secret;
}
