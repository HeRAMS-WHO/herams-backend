<?php

declare(strict_types=1);

namespace prime\objects;

use prime\objects\enums\Enum;
use yii\base\Arrayable;
use yii\base\NotSupportedException;
use yii\validators\RangeValidator;

use function iter\map;
use function iter\toArray;

/**
 * Models a set of values from an enum
 */
abstract class EnumSet implements \JsonSerializable, \IteratorAggregate, Arrayable, \Countable
{
    private array $values = [];

    /**
     * @return class-string
     */
    private static function getEnumClass(): string
    {
        if (!preg_match('/^prime\\\\objects\\\\(.*)Set$/', static::class, $matches)) {
            throw new \RuntimeException('Could not identify enum class for ' . static::class);
        }
        return "prime\\objects\\enums\\{$matches[1]}";
    }

    final public function __construct()
    {
    }

    public static function from(array|null $values): static
    {
        $result = new static();
        foreach ($values ?? [] as $value) {
            $result->add($value);
        }
        return $result;
    }

    public function add(string|int $value): void
    {
        $this->values[] = static::getEnumClass()::from($value);
    }

    public function jsonSerialize()
    {
        return $this->values;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->values);
    }

    /*****************************************************************************************/
    /* Functions below are here to support Yii's HTML helper which uses ArrayHelper::toArray */
    /*****************************************************************************************/
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return toArray(map(fn(Enum $enum) => $enum->value, $this->values));
    }

    public function fields()
    {
        throw new NotSupportedException();
    }

    public function extraFields()
    {
        throw new NotSupportedException();
    }

    /**
     * This is Yii2 specific...
     * @param string $attribute
     * @return array
     */
    public static function validatorFor(string $attribute): array
    {
        return [[$attribute], RangeValidator::class, 'allowArray' => true, 'range' => static::getEnumClass()::toValues()];
    }

    public function count(): int
    {
        return count($this->values);
    }

    public static function fullSet(): static
    {
        $result = new static();
        $result->values = static::getEnumClass()::cases();
        return $result;
    }
}
