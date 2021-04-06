<?php
declare(strict_types=1);

namespace prime\traits;

use yii\base\NotSupportedException;

trait ReadOnlyTrait
{

    public function beforeSave($insert): bool
    {
        throw new NotSupportedException('Model is read-only');
    }

    public function beforeValidate(): bool
    {
        throw new NotSupportedException('Model is read-only');
    }
}
