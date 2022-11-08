<?php

declare(strict_types=1);

namespace herams\common\attributes;

use Attribute;
use herams\common\interfaces\ValidateTypeInterface;

#[Attribute(Attribute::TARGET_CLASS + Attribute::IS_REPEATABLE)]
class SupportedType implements ValidateTypeInterface
{
    /**
     * @var class-string
     * @var class-string
     */
    public function __construct(
        private string $source,
        private string $target
    ) {
        // These checks have been disable to allow loading this in the frontend (where API models are not available)
//        if (! class_exists($source)) {
//            throw new \InvalidArgumentException("Class $source does not exist");
//        }
//        if (! class_exists($target)) {
//            throw new \InvalidArgumentException("Class $target does not exist");
//        }
    }

    public function validate(object $source, object $target): bool
    {
        return $source instanceof $this->source && $target instanceof $this->target;
    }
}
