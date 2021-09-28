<?php
declare(strict_types=1);

namespace prime\validators;

use prime\values\IntegerId;

class ExistValidator extends \yii\validators\ExistValidator
{
    protected function validateValue($value): array|null
    {
        if ($value instanceof IntegerId) {
            $value = $value->getValue();
        }

        return parent::validateValue($value);
    }
}
