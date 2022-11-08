<?php

declare(strict_types=1);

namespace herams\common\validators;

use herams\common\values\IntegerId;

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
