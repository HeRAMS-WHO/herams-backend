<?php

declare(strict_types=1);

namespace prime\models;

use yii\base\NotSupportedException;

abstract class ResponseModel extends RequestModel
{
    final public function rules(): array
    {
        return [];
    }

    final public function validate($attributeNames = null, $clearErrors = null): bool
    {
        throw new NotSupportedException('A response model should not use validation');
    }
}
