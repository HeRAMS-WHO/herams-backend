<?php
declare(strict_types=1);

namespace prime\validators;

use yii\validators\Validator;

class EnumValidator extends Validator
{
    /**
     * @var class-string
     */
    public string $enumClass;

    protected function validateValue($value): array|null
    {
        if ($this->enumClass::tryFrom($value) === null) {
            return [\Yii::t('app', "Invalid value {value} for enum"), ['value' => $value]];
        }
        return null;
    }
}
