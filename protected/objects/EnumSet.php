<?php
declare(strict_types=1);

namespace prime\objects;

/**
 * Models a set of values from an enum
 */
abstract class EnumSet implements \JsonSerializable, \IteratorAggregate
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

    public function jsonSerialize()
    {
        return $this->values;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->values);
    }
}
