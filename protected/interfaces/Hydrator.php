<?php

declare(strict_types=1);

namespace prime\interfaces;

interface Hydrator
{
    public static function fromDatabase(float|int|string|null $value): null|static;
}
