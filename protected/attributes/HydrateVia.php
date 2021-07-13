<?php
declare(strict_types=1);

namespace prime\attributes;

use Attribute;
use prime\interfaces\Hydrator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class HydrateVia
{
    /**
     * @var class-string $type
     */
    public function __construct(private string $type)
    {
        if (!is_subclass_of($type, Hydrator::class)) {
            throw new \InvalidArgumentException('Class does not exist or does not have implement Hydrator');
        }
    }

    /**
     * @param int|array|string|null $value
     */
    public function create(int|null|array|string $value): null|object
    {
        return $this->type::fromDatabase($value);
    }
}