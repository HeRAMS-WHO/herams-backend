<?php

declare(strict_types=1);

namespace prime\validators;

use Collecthor\DataInterfaces\VariableSetInterface;
use prime\helpers\HeramsVariableSet;
use yii\validators\Validator;
use function iter\toArray;

class VariableValidator extends Validator
{
    public VariableSetInterface $variableSet;

    public bool $allowArray = false;

    protected function validateValue($value)
    {
        if (is_array($value) && ! $this->allowArray) {
            return ['Expected one variable, got a list of variables', []];
        }

        $variables = is_array($value) ? $value : [$value];

        $dictionary = [];
        foreach ($this->variableSet->getVariableNames() as $variableName) {
            $dictionary[$variableName] = true;
        }

        $invalid = [];
        foreach ($variables as $variableName) {
            if (! isset($dictionary[$variableName])) {
                $invalid[] = $variableName;
            }
        }

        if (! empty($invalid)) {
            return ["The following variables are invalid: {names}", [
                'names' => implode(',', $invalid),
            ]];
        }
        return null;
    }

    public static function singleFromSet(VariableSetInterface $variableSet, string ...$attributes): static
    {
        $result = new self();
        $result->variableSet = $variableSet;
        $result->attributes = $attributes;
        return $result;
    }

    public static function multipleFromSet(VariableSetInterface $variableSet, string ...$attributes): static
    {
        $result = static::singleFromSet($variableSet, ...$attributes);
        $result->allowArray = true;
        return $result;
    }
}
