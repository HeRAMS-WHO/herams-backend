<?php

declare(strict_types=1);

namespace herams\common\attributes;

use Attribute;

/**
 * Set the target field for a model property
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Field
{
    public function __construct(
        public readonly string $field
    ) {
    }
}
