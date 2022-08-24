<?php
declare(strict_types=1);

namespace prime\validators;

use prime\helpers\LocalizedString;
use yii\validators\Validator;

final class LocalizedStringValidator extends Validator
{
    protected function validateValue($value): null|array
    {
        try {
            $cast = new LocalizedString($value);
        } catch (\Throwable $t) {
            return [$t->getMessage(), []];
        }
        return null;
    }


}
