<?php

declare(strict_types=1);

namespace prime\interfaces;

interface Dehydrator
{
    public static function fromForm(string|null $value): null|static;
}
