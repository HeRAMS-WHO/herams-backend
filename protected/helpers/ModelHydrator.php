<?php
declare(strict_types=1);

namespace prime\helpers;

use prime\models\ActiveRecord;
use prime\objects\enums\Enum;
use prime\objects\EnumSet;
use prime\values\IntegerId;
use yii\base\Model;
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

    private function castValue(Model $model, string $attribute, $value)
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
                }
                throw new \InvalidArgumentException("Attribute $attribute has a complex type: {$property->getName()}");
            }


            if ($property->allowsNull() && ($value === "" || $value === null)) {
                return null;
            }

            return match ($property->getName()) {
                'string' => $value,
                'int' => $this->castInt($value),
                'float' => $this->castFloat($value),
                'bool' => $this->castBool($value),
                'array' => $this->castArray($value),
                default => die("Unknown type: {$property->getName()} for property $attribute")
            };
        } catch (\Throwable $t) {
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
                $model->$attribute = $this->castValue($model, $attribute, $data[$attribute]);
            }
        }
    }

    public function hydrateFromActiveRecord(Model $model, ActiveRecord $record): void
    {
        $model->ensureBehaviors();
        foreach ($record->attributes as $key => $value) {
            if ($model->canSetProperty($key)) {
                $model->$key = $this->castValue($model, $key, $record->$key);
            }
        }
    }

    private function castSimple(bool|float|int|string|array|object|null $complex): bool|float|int|string|array|null
    {
        if (is_object($complex)) {
            if ($complex instanceof Enum) {
                return $complex->value;
            } elseif (is_iterable($complex)) {
                return toArray($complex);
            } elseif ($complex instanceof IntegerId) {
                return $complex->getValue();
            } else {
                throw new \InvalidArgumentException("Unknown complex type: " . get_class($complex));
            }
        }
        return $complex;
    }

    public function hydrateActiveRecord(ActiveRecord $record, Model $model): void
    {
        foreach ($model->attributes as $key => $value) {
            if ($record->canSetProperty($key)) {
                $record->$key = $this->castSimple($model->$key);
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
