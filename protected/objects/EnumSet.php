<?php
declare(strict_types=1);

namespace prime\objects;

use prime\objects\enums\Enum;
use yii\base\Arrayable;
use yii\base\NotSupportedException;
use function iter\map;
use function iter\toArray;

/**
 * Models a set of values from an enum
 */
abstract class EnumSet implements \JsonSerializable, \IteratorAggregate, Arrayable
{
    private array $values = [];
    /** @var class-string  */
    private string $class;

    final public function __construct()
    {
    }

    public static function from(array $values): static
    {
        if (!preg_match('/^prime\\\\objects\\\\(.*)Set$/', static::class, $matches)) {
            throw new \RuntimeException('Could not identify enum class for ' . static::class);
        }
        $result = new static();
        $result->class = "prime\\objects\\enums\\{$matches[1]}";
        foreach ($values as $value) {
            $result->add($value);
        }
        return $result;
    }

    public function add(string|int $value): void
    {
        $this->values[] = $this->class::from($value);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->values;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->values);
    }

    /*****************************************************************************************/
    /* Functions below are here to support Yiis HTML helper which uses ArrayHelper::toArrays */
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
}
