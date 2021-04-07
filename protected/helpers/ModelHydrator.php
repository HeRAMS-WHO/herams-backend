<?php
declare(strict_types=1);

namespace prime\helpers;

use yii\base\Model;
use yii\web\Request;

class ModelHydrator
{

    private function castInt(string $value): int
    {
        if (!preg_match('/^\d+$/', $value)) {
            throw new \InvalidArgumentException("String must consist of digits only");
        }
        return (int) $value;
    }

    private function castBool(string $value): bool
    {
        return $value === "1";
    }

    private function castFloat(string $value): float
    {
        if (!preg_match('/^\d+(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException("String must match \d+(.\d+)");
        }
        return (float) $value;
    }

    private function castArray(string $value): array
    {
        return json_decode($value, true);
    }

    private function castValue(Model $model, string $attribute, string $value)
    {
        $rc = new \ReflectionClass($model);
        if (!$rc->hasProperty($attribute)) {
            return $value;
            // THIS IS TEMPORARY, THIS ASSUMES ALL MAGIC PROPERTIES ARE JSON
            return $this->castArray($value);
        }
        /** @var \ReflectionNamedType $property */
        $property = $rc->getProperty($attribute)->getType();

        if (!$property->isBuiltin()) {
            throw new \InvalidArgumentException("Attribute $attribute has a complex type");
        }

        if ($property->allowsNull() && $value === "") {
            return null;
        }

        return match ($property->getName()) {
            'string' => $value,
            'int' => $this->castInt($value),
            'float' => $this->castFloat($value),
            'bool' => $this->castBool($value),
        default => die($property->getName())
        };
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

    public function hydrateFromRequest(Model $model, Request $request): void
    {
        if ($request->isPost || $request->isPut) {
            $this->hydrateFromRequestArray($model, $request->bodyParams[$model->formName()]);
            return;
        }
        throw new \InvalidArgumentException("Could not extract data from request");
    }
}
