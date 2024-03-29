<?php

declare(strict_types=1);

namespace herams\common\attributes;

use Attribute;
use prime\interfaces\Dehydrator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DehydrateVia
{
    /**
     * @var class-string
     */
    public function __construct(
        private string $type
    ) {
        if (! is_subclass_of($type, Dehydrator::class)) {
            throw new \InvalidArgumentException('Class does not exist or does not have implement Dehydrator');
        }
    }

    public function create(int|array|string|null $value): int|array|string|null|object
    {
        return $this->type::fromForm($value);
    }
}
