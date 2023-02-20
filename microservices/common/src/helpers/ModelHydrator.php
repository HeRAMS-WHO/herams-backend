<?php

declare(strict_types=1);

namespace herams\common\helpers;

use BackedEnum;
use Collecthor\DataInterfaces\RecordInterface;
use herams\common\attributes\DehydrateVia;
use herams\common\attributes\Field;
use herams\common\attributes\HydrateVia;
use herams\common\attributes\JsonField;
use herams\common\attributes\SourcePath;
use herams\common\attributes\SupportedType;
use herams\common\enums\HydrateSource;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\ModelHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\values\Id;
use herams\common\values\IntegerId;
use herams\common\values\Latitude;
use herams\common\values\Longitude;
use herams\common\values\StringId;
use prime\objects\enums\Enum;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UnitEnum;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\BaseArrayHelper;
use yii\helpers\Inflector;
use yii\web\Request;
use function iter\toArray;

#[SupportedType(Model::class, \yii\db\ActiveRecord::class)]
class ModelHydrator implements ActiveRecordHydratorInterface, ModelHydratorInterface
{
    /**
     * Version of Yii's canSetProperty that respects visibility.
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

    private function castFloat(string|int|float $value): float
    {
        if (is_string($value) && ! preg_match('/^-?\d+(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException("String must match \d+(.\d+)");
        }
        return (float) $value;
    }

    private function castForDatabase(bool|float|int|string|array|object|null $complex): bool|float|int|string|array|null|Expression
    {
        if (is_object($complex)) {
            return match (true) {
                $complex instanceof BackedEnum => $complex->value,
                $complex instanceof UnitEnum => $complex->name,
                $complex instanceof Enum => $complex->value,
                $complex instanceof UuidInterface => $complex->getBytes(),
                is_iterable($complex) => toArray($complex),
                $complex instanceof Id => $complex->getValue(),
                $complex instanceof Expression => $complex,
                $complex instanceof RecordInterface => $complex->allData(),
                default => throw new \InvalidArgumentException("Unknown complex type: " . get_class($complex))
            };
        } elseif (is_bool($complex)) {
            return $complex ? 1 : 0;
        }
        return $complex;
    }

    private function castInt(bool|int|string $value, bool $optional = false): int|null
    {
        if (is_string($value) && $optional && $value === "") {
            return null;
        }
        if (is_string($value) && ! preg_match('/^-?\d+$/', $value)) {
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
        if (! $property->isBuiltin()) {
            $name = $property->getName();

            return match (true) {
                $name === LocalizedString::class => $this->castLocalizedString($value, $name),
                $name === Latitude::class => new Latitude((float) $value),
                $name === Longitude::class => new Longitude((float) $value),
                $name === RecordInterface::class => isset($value) ? new NormalizedArrayDataRecord($value) : null,
                is_subclass_of($name, BackedEnum::class) => $this->castBackedEnum($value, $name, $source),
                is_subclass_of($name, Enum::class) => $this->castEnum($value, $name),
                is_subclass_of($name, IntegerId::class) => $this->castIntegerId($value, $name, $property->allowsNull()),
                is_subclass_of($name, StringId::class) => $this->castStringId($value, $name),
                is_subclass_of($name, UuidInterface::class) || $name === UuidInterface::class => $this->castUuid($value, $name, $source),

                default => throw new \InvalidArgumentException("Attribute $attribute has a complex type: {$property->getName()}")
            };
        }

        if ($property->allowsNull() && ($value === "" || $value === null)) {
            return null;
        }

        if (! $property->allowsNull() && $value === null) {
            throw new \RuntimeException("Property {$property->getName()} does not allow null, but value is null");
        }

        return match ($property->getName()) {
            'string' => (string) $value,
            'int' => $this->castInt($value),
            'float' => $this->castFloat($value),
            'bool' => $this->castBool($value),
            'array' => $this->castArray($value),
            'mixed' => $value,
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

    private function getTypeForAttribute(Model $model, string $property): \ReflectionNamedType
    {
        $rc = new \ReflectionClass($model);
        // Simple property
        if ($rc->hasProperty($property)) {
            return $rc->getProperty($property)->getType();
        }
        // Property with setter.
        $setter = "set" . ucfirst($property);
        if ($rc->hasMethod($setter)) {
            $method = $rc->getMethod($setter);
            if ($method->isPublic() && $method->getNumberOfRequiredParameters() === 1) {
                return $method->getParameters()[0]->getType();
            }
        }
        throw new \RuntimeException("Could not resolve type");
    }

    private function castValue(Model $model, string $attribute, mixed $value, HydrateSource $source): mixed
    {
        try {
            $type = $this->getTypeForAttribute($model, $attribute);
            return $this->castType($type, $value, $attribute, $source);
        } catch (\Throwable $t) {
            \Yii::error($t);
            $model->addError($attribute, $t->getMessage());
        }
        return null;
    }

    public function hydrateActiveRecord(Model $model, ActiveRecord $record): void
    {
        $reflectionClass = new \ReflectionClass($model);
        $jsonFields = [];
        foreach ($model->attributes as $key => $value) {
            $field = $key;
            if ($reflectionClass->hasProperty($key)) {
                $reflectionProperty = $reflectionClass->getProperty($key);
                // Field renaming via PHP8 attributes
                $field = ($reflectionProperty->getAttributes(Field::class)[0] ?? null)?->newInstance()->field ?? $key;

                // Special dehydrator
                foreach ($reflectionProperty->getAttributes(DehydrateVia::class) as $attribute) {
                    if ($attribute->getName() === DehydrateVia::class) {
                        $value = $attribute->newInstance()->create($value);
                    }
                }

                // Handle JSON fields.
                if (null !== $jsonField = ($reflectionProperty->getAttributes(JsonField::class)[0] ?? null)?->newInstance()->field) {
                    $jsonFields[$jsonField] ??= [];
                    $jsonFields[$jsonField][$field] = $this->castForDatabase($value);
                    continue;
                }
            }
            if ($record->canSetProperty($field)) {
                $record->$field = $this->castForDatabase($value);
            }
        }

        foreach ($jsonFields as $field => $value) {
            if ($record->canSetProperty($field)) {
                $record->$field = $value;
            }
        }
    }

    /**
     * Hydrates a constructor call using the models' properties as the source.
     * Supports a source that uses id casing with a target that uses camelcase.
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    public function hydrateConstructor(Model $source, string $class): object
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
                HydrateSource::database
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
                $target->$key = $this->castValue($target, $key, $value, HydrateSource::database);
            }
        }
    }

    /**
     * @param array $data Array data extracted from a HTTP request (so all values are strings)
     */
    public function hydrateFromRequestArray(Model $model, array $data): void
    {
        foreach ($model->safeAttributes() as $attribute) {
            if (isset($data[$attribute])) {
                $model->$attribute = $this->castValue($model, $attribute, $data[$attribute], HydrateSource::webForm);
            }
        }
    }

    private function getPath(Model $model, string $attribute): array
    {
        $reflectionClass = new \ReflectionClass($model);
        if ($reflectionClass->hasProperty($attribute)) {
            $property = $reflectionClass->getProperty($attribute);
            /** @var \ReflectionAttribute<SourcePath> $propertyAttribute */
            foreach ($property->getAttributes(SourcePath::class) as $propertyAttribute) {
                return $propertyAttribute->newInstance()->path;
            }
        }

        return [$attribute];
    }

    private function pathExists(array $path, array $data): bool
    {
        return $this->getValue($path, $data) !== null;
    }

    private function getValue(array $path, array $data): mixed
    {
        return BaseArrayHelper::getValue($data, $path);
    }

    /**
     * Hydrates a model from a json dictionary
     * @param array $data Array data extracted from JSON
     */
    public function hydrateFromJsonDictionary(Model $model, array $data): void
    {
        foreach ($model->safeAttributes() as $attribute) {
            $path = $this->getPath($model, $attribute);

            if ($this->pathExists($path, $data)) {
                try {
                    $value = $this->castValue($model, $attribute, $this->getValue($path, $data), HydrateSource::json);
                    $model->$attribute = $value;
                } catch (\InvalidArgumentException $e) {
                    $model->addError($attribute, $e->getMessage());
                }
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

    private function castBackedEnum($value, string $name, HydrateSource $source): BackedEnum
    {
        $reflectionEnum = new \ReflectionEnum($name);

        $backingType = $reflectionEnum->getBackingType();

        if (! $backingType instanceof \ReflectionNamedType) {
            throw new \Exception("Could not find backing type for enum of type $name");
        }

        return $name::from(match ($backingType->getName()) {
            'int' => $this->castInt($value, false),
            'string' => $value
        });
    }

    /**
     * @param string|array<string, string> $value
     */
    private function castLocalizedString(string|array $value, string $name): LocalizedString
    {
        return new LocalizedString($value);
    }

    public function hydrateRequestModel(ActiveRecord $source, \herams\common\models\RequestModel $target): void
    {
        $this->hydrateFromActiveRecord($source, $target);
    }
}
