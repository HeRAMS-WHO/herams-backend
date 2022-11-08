<?php

declare(strict_types=1);

namespace herams\common\attributes;

use Attribute;

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
