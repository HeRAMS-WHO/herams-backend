<?php
declare(strict_types=1);

namespace prime\helpers;

use CrEOF\Geo\WKB\Parser;
use prime\attributes\DehydrateVia;
use prime\attributes\HydrateVia;
use prime\models\ActiveRecord;
use prime\objects\enums\Enum;
use prime\objects\enums\HydrateSource;
use prime\objects\EnumSet;
use prime\values\Geometry;
use prime\values\IntegerId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use yii\base\Model;
use yii\db\Expression;
use yii\web\Request;
use function iter\toArray;

class ModelHydrator
{

    private function castInt(int|string $value): int
    {
        if (is_string($value) && !preg_match('/^\d+$/', $value)) {
            throw new \InvalidArgumentException("String must consist of digits only");
        }
        return (int) $value;
    }

    private function castBool(string|int $value): bool
    {
        return $this->castInt($value) === 1;
    }

    private function castFloat($value): float
    {
        if (is_string($value) && !preg_match('/^\d+(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException("String must match \d+(.\d+)");
        }
        return (float) $value;
    }

    private function castArray(array|string $value): array
    {
        return is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * @param class-string $class
     */
    private function castIntegerId($value, string $class): IntegerId
    {
        return new $class($this->castInt($value));
    }

    /**
     * @param class-string $class
     */
    private function castEnumSet(null|array $value, string $class): EnumSet
    {
        return $class::from($value ?? []);
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

    private function castWKBToGeometry(string $value): Geometry
    {
        $parser = new Parser();
        $data = $parser->parse(substr($value, 4));
        $data['srid'] = unpack('i', $value)[1];
        return Geometry::fromParsedArray($data);
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

    /**
     * @param class-string $class
     */
    private function castUuid(string $value, string $class, HydrateSource $source): UuidInterface
    {
        return Uuid::fromBytes($value);
    }

    private function castValue(Model $model, string $attribute, mixed $value, HydrateSource $source)
    {
        try {
            $rc = new \ReflectionClass($model);
            if (!$rc->hasProperty($attribute)) {
                return (string)$value;
            }
            /** @var \ReflectionNamedType $property */
            $property = $rc->getProperty($attribute)->getType();

            if (!$property->isBuiltin()) {
                $name = $property->getName();
                if (is_subclass_of($name, EnumSet::class)) {
                    return $this->castEnumSet($value, $name);
                } elseif (is_subclass_of($name, Enum::class)) {
                    return $this->castEnum($value, $name);
                } elseif (is_subclass_of($name, IntegerId::class)) {
                    return $this->castIntegerId($value, $name);
                } elseif (is_subclass_of($name, Geometry::class)) {
                    return $this->castGeometry($value, $name, $source);
                } elseif (is_subclass_of($name, UuidInterface::class) || $name === UuidInterface::class) {
                    return $this->castUuid($value, $name, $source);
                }

                throw new \InvalidArgumentException("Attribute $attribute has a complex type: {$property->getName()}");
            }


            if ($property->allowsNull() && ($value === "" || $value === null)) {
                return null;
            }

            return match ($property->getName()) {
                'string' => (string) $value,
                'int' => $this->castInt($value),
                'float' => $this->castFloat($value),
                'bool' => $this->castBool($value),
                'array' => $this->castArray($value),
                default => die("Unknown type: {$property->getName()} for property $attribute")
            };
        } catch (\Throwable $t) {
            $model->addError($attribute, $t->getMessage());
            return null;
            throw new \RuntimeException("Failed to cast value for attribute $attribute", 0, $t);
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
     * Version if Yii's canSetProperty that respects visibility.
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

    public function hydrateFromActiveRecord(Model $model, ActiveRecord $record): void
    {
        $model->ensureBehaviors();
        $reflectionClass = new \ReflectionClass($model);
        foreach ($record->attributes as $key => $value) {
            if ($reflectionClass->hasProperty($key)) {
                foreach ($reflectionClass->getProperty($key)->getAttributes() as $attribute) {
                    if ($attribute->getName() === HydrateVia::class) {
                        $value = $attribute->newInstance()->create($value);
                    }
                }
            }
            if ($this->canSetProperty($model, $key)) {
                $model->$key = $this->castValue($model, $key, $value, HydrateSource::database());
            }
        }
    }

    private function castForDatabase(bool|float|int|string|array|object|null $complex): bool|float|int|string|array|null|Expression
    {
        if (is_object($complex)) {
            if ($complex instanceof Enum) {
                return $complex->value;
            } elseif ($complex instanceof UuidInterface) {
                return $complex->getBytes();
            } elseif (is_iterable($complex)) {
                return toArray($complex);
            } elseif ($complex instanceof IntegerId) {
                return $complex->getValue();
            } elseif ($complex instanceof Geometry) {
                return $complex->toWKT();
            } elseif ($complex instanceof Expression) {
                return $complex;
            } else {
                throw new \InvalidArgumentException("Unknown complex type: " . get_class($complex));
            }
        }
        return $complex;
    }

    public function hydrateActiveRecord(ActiveRecord $record, Model $model): void
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

    public function hydrateFromRequestBody(Model $model, Request $request): void
    {
        if ($request->isPost || $request->isPut) {
            $this->hydrateFromRequestArray($model, $request->bodyParams[$model->formName()]);
            return;
        }
        throw new \InvalidArgumentException("Could not extract data from request");
    }
}
