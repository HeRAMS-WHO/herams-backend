<?php

declare(strict_types=1);

namespace prime\objects;

use BackedEnum;
use herams\common\validators\BackedEnumValidator;
use prime\objects\enums\Enum;
use yii\base\Arrayable;
use yii\base\NotSupportedException;
use function iter\map;
use function iter\toArray;

/**
 * Models a set of values from an enum
 */
abstract class EnumSet implements \JsonSerializable, \IteratorAggregate, Arrayable, \Countable
{
    /**
     * @var list<BackedEnum>
     */
    private array $values = [];

    /**
     * @return class-string<BackedEnum>
     */
    private static function getEnumClass(): string
    {
        if (! preg_match('/^prime\\\\objects\\\\(.*)Set$/', static::class, $matches)) {
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
            if (! is_subclass_of($value, self::getEnumClass(), false)) {
                $result->add(self::getEnumClass()::from($value));
            } else {
                $result->add($value);
            }
        }
        return $result;
    }

    public function add(\UnitEnum $value): void
    {
        $this->values[] = $value;
    }

    public function jsonSerialize(): array
    {
        return $this->values;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->values);
    }

    /*****************************************************************************************/
    /* Functions below are here to support Yii's HTML helper which uses ArrayHelper::toArray */
    /*****************************************************************************************/
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return toArray(map(fn (Enum|BackedEnum $enum) => $enum->value, $this->values));
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
     */
    public static function validatorFor(string $attribute): array
    {
        return [[$attribute],
            BackedEnumValidator::class,
            'allowArray' => true,
            'example' => static::getEnumClass()::cases()[0],
        ];
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
