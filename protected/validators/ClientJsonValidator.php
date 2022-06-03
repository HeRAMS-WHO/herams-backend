<?php

declare(strict_types=1);

namespace prime\validators;

use yii\validators\Validator;

class ClientJsonValidator extends Validator
{
    protected function validateValue($value): array|null
    {
        if (! is_array($value)) {
            return ['Value is not an array', []];
        }
        return null;
    }

    public function clientValidateAttribute($model, $attribute, $view): string
    {
        return <<<JAVASCRIPT
            try {
                JSON.parse(value);
            } catch (e) {
                messages.push(e);
            }
        JAVASCRIPT;
    }
}
