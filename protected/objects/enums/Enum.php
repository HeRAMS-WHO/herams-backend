<?php

declare(strict_types=1);

namespace prime\objects\enums;

use prime\interfaces\Dehydrator;
use prime\validators\EnumValidator;

abstract class Enum extends \Spatie\Enum\Enum implements Dehydrator
{
    final protected function __construct(int|string $value)
    {
        parent::__construct($value);
    }

    public static function fromForm(string|null $value): null|static
    {
        // Challenge here with numeric string vs int
        return $value !== null ? static::from(is_numeric($value) ? (int)$value : $value) : null;
    }

    /**
     * This is Yii2 specific...
     * @param string $attribute
     * @return array
     */
    public static function validatorFor(string $attribute): array
    {
        return [[$attribute], EnumValidator::class, 'enumClass' => static::class];
    }
}
