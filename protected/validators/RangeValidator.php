<?php

declare(strict_types=1);

namespace prime\validators;

use herams\common\values\IntegerId;

class RangeValidator extends \yii\validators\RangeValidator
{
    protected function validateValue($value): array|null
    {
        if ($value instanceof IntegerId) {
            $value = $value->getValue();
        }

        return parent::validateValue($value);
    }
}
