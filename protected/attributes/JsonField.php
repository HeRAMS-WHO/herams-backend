<?php

declare(strict_types=1);

namespace prime\attributes;

use Attribute;
use prime\interfaces\Dehydrator;

/**
 * Set the target field for a model property
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonField
{
    public function __construct(public readonly string $field)
    {
    }


}
