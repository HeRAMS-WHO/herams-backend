<?php

declare(strict_types=1);

namespace prime\helpers;

use BackedEnum;
use CrEOF\Geo\WKB\Parser;
use prime\attributes\DehydrateVia;
use prime\attributes\HydrateVia;
use prime\attributes\SupportedType;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\ModelHydratorInterface;
use prime\models\ActiveRecord;
use prime\objects\enums\Enum;
use prime\objects\enums\HydrateSource;
use prime\objects\enums\Language;
use prime\objects\EnumSet;
use prime\values\Geometry;
use prime\values\Id;
use prime\values\IntegerId;
use prime\values\StringId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UnitEnum;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\Inflector;
use yii\web\Request;
use function iter\mapWithKeys;
use function iter\toArray;

#[SupportedType(Model::class, \yii\db\ActiveRecord::class)]
class ModelHydrator implements ActiveRecordHydratorInterface, ModelHydratorInterface
{
    /**
     * Version of Yii's canSetProperty that respects visibility.
     * @param Model $model
     * @return bool
     */
    private function canSetProperty(Model $model, $attribute): bool
    {
        if (method_exists($model, 'set' . $attribute)) {
            return true;
        }
        $rc = new \ReflectionClass($model);
        if ($rc->hasProperty($attribute) && $rc->getProperty($attribute)->isPublic()) {
            return true;
        }

        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior->canSetProperty($attribute)) {
                return true;
            }
        }
        return false;
    }

    private function castArray(array|string $value): array
    {
        return is_array($value) ? $value : json_decode($value, true);
    }

    private function castBool(bool|string|int $value): bool
    {
        return $this->castInt($value) === 1;
    }

    /**
     * @param class-string $class
     */
    private function castEnum(string|int $value, string $class): Enum
    {
        if (is_string($value) && preg_match('/^\d+$/', $value)) {
            $value = (int) $value;
        }
        return $class::from($value);
    }

    /**
     * @param class-string $class
     */
    private function castEnumSet(string|null|array $value, string $class): EnumSet
    {
        // This will still crash on non-empty strings, that is intended. If a string is passed it has to be empty
        return $class::from(!empty($value) ? $value : []);
    }

    private function castFloat(string|int|float $value): float
    {
        if (is_string($value) && !preg_match('/^-?\d+(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException("String must match \d+(.\d+)");
        }
        return (float) $value;
    }

    private function castForDatabase(bool|float|int|string|array|object|null $complex): bool|float|int|string|array|null|Expression
    {
        if (is_object($complex)) {
            return match(true) {
                $complex instanceof BackedEnum => $complex->value,
                $complex instanceof UnitEnum => $complex->name,
                $complex instanceof Enum => $complex->value,
                $complex instanceof UuidInterface => $complex->getBytes(),
                is_iterable($complex) => toArray($complex),
                $complex instanceof Id => $complex->getValue(),
                $complex instanceof Geometry => $complex->toWKT(),
                $complex instanceof Expression => $complex,
                default => throw new \InvalidArgumentException("Unknown complex type: " . get_class($complex))
            };
        } elseif (is_bool($complex)) {
            return $complex ? 1 : 0;
        }
        return $complex;
    }

    /**
     * @param class-string $class
     */
    private function castGeometry(null|string $value, string $class, HydrateSource $source): Geometry|null
    {
        if (empty($value)) {
            return null;
        }

        return match ($source) {
            HydrateSource::database() => $this->castWKBToGeometry($value),
            HydrateSource::webForm() => $class::fromString($value)
        };
    }
    private function castInt(bool|int|string $value, bool $optional = false): int|null
    {
        if (is_string($value) && $optional && $value === "") {
            return null;
        }
        if (is_string($value) && !preg_match('/^-?\d+$/', $value)) {
            throw new \InvalidArgumentException("String must consist of digits only, got: $value");
        }
        return (int) $value;
    }

    /**
     * @param class-string $class
     */
    private function castIntegerId($value, string $class, bool $optional = false): IntegerId|null
    {
        $int = $this->castInt($value, $optional);
        if ($optional && $int === null) {
            return null;
        }
        return new $class($int);
    }

    /**
     * @param class-string $class
     */
    private function castStringId($value, string $class): StringId
    {
        return new $class((string) $value);
    }

    private function castType(\ReflectionNamedType $property, mixed $value, string $attribute, HydrateSource $source)
    {
        if (!$property->isBuiltin()) {
            $name = $property->getName();
            return match(true) {
                $name === LocalizedString::class => $this->castLocalizedString($value, $name),
                is_subclass_of($name, BackedEnum::class) => $this->castBackedEnum($value, $name, $source),
                is_subclass_of($name, EnumSet::class) => $this->castEnumSet($value, $name),
                is_subclass_of($name, Enum::class) => $this->castEnum($value, $name),
                is_subclass_of($name, IntegerId::class) => $this->castIntegerId($value, $name, $property->allowsNull()),

                is_subclass_of($name, StringId::class) => $this->castStringId($value, $name),
                is_subclass_of($name, Geometry::class) => $this->castGeometry($value, $name, $source),
                is_subclass_of($name, UuidInterface::class)  || $name === UuidInterface::class => $this->castUuid($value, $name, $source),

                default => throw new \InvalidArgumentException("Attribute $attribute has a complex type: {$property->getName()}")
            };



        }

        if ($property->allowsNull() && ($value === "" || $value === null)) {
            return null;
        }

        if (!$property->allowsNull() && $value === null) {
            throw new \RuntimeException("Property {$property->getName()} does not allow null, but value is null");
        }

        return match ($property->getName()) {
            'string' => (string) $value,
            'int' => $this->castInt($value),
            'float' => $this->castFloat($value),
            'bool' => $this->castBool($value),
            'array' => $this->castArray($value),
            default => die("Unknown type: {$property->getName()} for property $attribute")
        };
    }

    /**
     * @param class-string $class
     */
    private function castUuid(string $value, string $class, HydrateSource $source): UuidInterface
    {
        return Uuid::fromBytes($value);
    }

    private function castValue(Model $model, string $attribute, mixed $value, HydrateSource $source): mixed
    {
        try {
            $rc = new \ReflectionClass($model);
            if (!$rc->hasProperty($attribute)) {
                return (string)$value;
            }
            /** @var \ReflectionNamedType $property */
            $property = $rc->getProperty($attribute)->getType();
            return $this->castType($property, $value, $attribute, $source);
        } catch (\Throwable $t) {
            throw $t;
            $model->addError($attribute, $t->getMessage());
            throw new \RuntimeException("Failed to cast value for attribute $attribute", 0, $t);
        }
    }

    private function castWKBToGeometry(string $value): Geometry
    {
        $parser = new Parser();
        $data = $parser->parse(substr($value, 4));
        $data['srid'] = unpack('i', $value)[1];
        return Geometry::fromParsedArray($data);
    }

    public function hydrateActiveRecord(Model $model, ActiveRecord $record): void
    {
        $reflectionClass = new \ReflectionClass($model);
        foreach ($model->attributes as $key => $value) {
            if ($reflectionClass->hasProperty($key)) {
                foreach ($reflectionClass->getProperty($key)->getAttributes() as $attribute) {
                    if ($attribute->getName() === DehydrateVia::class) {
                        $value = $attribute->newInstance()->create($value);
                    }
                }
            }
            if ($record->canSetProperty($key)) {
                $record->$key = $this->castForDatabase($value);
            }
        }
    }

    /**
     * Hydrates a constructor call using the models' properties as the source.
     * Supports a source that uses id casing with a target that uses camelcase.
     * @template T of object
     * @param class-string<T> $class
     * @return T|null
     */
    public function hydrateConstructor(Model $source, string $class): object|null
    {
        $reflectionClass = new \ReflectionClass($class);
        $args = [];
        foreach ($reflectionClass->getConstructor()->getParameters() as $parameter) {
            $camelCased = Inflector::underscore($parameter->getName());
            if ($source->canGetProperty($camelCased)) {
                $rawValue = $source->{$camelCased};
            } elseif ($source->canGetProperty($parameter->getName())) {
                $rawValue = $source->{$parameter->getName()};
            } else {
                $rawValue = null;
            }
            $args[] = $this->castType(
                $parameter->getType(),
                $rawValue,
                $parameter->getName(),
                HydrateSource::database()
            );
        }
        return $reflectionClass->newInstanceArgs($args);
    }

    public function hydrateFromActiveRecord(ActiveRecord $source, Model $target): void
    {
        $target->ensureBehaviors();
        $reflectionClass = new \ReflectionClass($target);
        foreach ($source->attributes as $key => $value) {
            if ($reflectionClass->hasProperty($key)) {
                foreach ($reflectionClass->getProperty($key)->getAttributes() as $attribute) {
                    if ($attribute->getName() === HydrateVia::class) {
                        $value = $attribute->newInstance()->create($value);
                    }
                }
            }
            if ($this->canSetProperty($target, $key)) {
                $target->$key = $this->castValue($target, $key, $value, HydrateSource::database());
            }
        }
    }

    /**
     * @param Model $model
     * @param array $data Array data extracted from a HTTP request (so all values are strings)
     */
    public function hydrateFromRequestArray(Model $model, array $data): void
    {
        foreach ($model->safeAttributes() as $attribute) {

            if (isset($data[$attribute])) {
                $model->$attribute = $this->castValue($model, $attribute, $data[$attribute], HydrateSource::webForm());
            }
        }
    }

    /**
     * @param Model $model
     * @param array $data Array data extracted from JSON
     */
    public function hydrateFromJsonDictionary(Model $model, array $data): void
    {
        foreach ($model->safeAttributes() as $attribute) {
            if (isset($data[$attribute])) {
                $value = $this->castValue($model, $attribute, $data[$attribute], HydrateSource::json());
                \Yii::warning(["Setting", $attribute, $data[$attribute], $value]);

                $model->$attribute = $value;
            }
        }
    }

    public function hydrateFromRequestBody(Model $model, Request $request): void
    {
        if ($request->getIsPost() || $request->getIsPut()) {
            $this->hydrateFromRequestArray($model, $request->getBodyParams()[$model->formName()]);
            return;
        }
        throw new \InvalidArgumentException("Could not extract data from request");
    }

    public function hydrateFromRequestQuery(Model $model, Request $request): void
    {
        // No check for request type since query params also come with other methods
        $this->hydrateFromRequestArray($model, $request->getQueryParams()[$model->formName()] ?? []);
    }

    /**
     * @param $value
     * @param class-string<UnitEnum> $name
     * @param HydrateSource $source
     * @return UnitEnum
     */
    private function castUnitEnum($value, string $name, HydrateSource $source): UnitEnum
    {
        return $name::from($value);
    }

    private function castBackedEnum($value, string $name, HydrateSource $source): BackedEnum
    {
        $reflectionEnum = new \ReflectionEnum($name);

        $backingType = $reflectionEnum->getBackingType();

        if (!$backingType instanceof \ReflectionNamedType) {
            throw new \Exception("Could not find backing type for enum of type $name");
        }

        return $name::from(match($backingType->getName()) {
            'int' => $this->castInt($value, false),
            'string' => $value
        });
    }

    /**
     * @param string|array<string, string> $value
     * @param string $name
     * @return LocalizedString
     */
    private function castLocalizedString(string|array $value, string $name): LocalizedString
    {
        return new LocalizedString($value);
    }
}
