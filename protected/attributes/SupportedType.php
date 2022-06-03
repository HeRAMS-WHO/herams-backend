<?php

declare(strict_types=1);

namespace prime\attributes;

use Attribute;
use prime\interfaces\Hydrator;
use prime\interfaces\ValidateTypeInterface;
use yii\base\Model;
use yii\db\ActiveRecord;

#[Attribute(Attribute::TARGET_CLASS)]
class SupportedType implements ValidateTypeInterface
{
    /**
     * @var class-string $source
     * @var class-string $target
     */
    public function __construct(private string $source, private string $target)
    {
        if (!class_exists($source)) {
            throw new \InvalidArgumentException("Class $source does not exist");
        }
        if (!class_exists($target)) {
            throw new \InvalidArgumentException("Class $target does not exist");
        }
    }

    public function validate(object $source, object $target): bool
    {
        return $source instanceof $this->source && $target instanceof $this->target;
    }
}
