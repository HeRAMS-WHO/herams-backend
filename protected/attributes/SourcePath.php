<?php

declare(strict_types=1);

namespace prime\attributes;

use Attribute;
use prime\interfaces\Hydrator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class SourcePath
{
    /**
     * @var list<string>
     */
    public function __construct(public readonly array $path)
    {
    }
}
