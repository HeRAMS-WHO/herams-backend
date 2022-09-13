<?php

declare(strict_types=1);

namespace prime\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class SourceField
{
    public function __construct(public readonly string $field)
    {
    }
}
