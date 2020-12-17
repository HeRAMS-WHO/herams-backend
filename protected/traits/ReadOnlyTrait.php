<?php
declare(strict_types=1);

namespace prime\traits;

use yii\base\NotSupportedException;

trait ReadOnlyTrait
{

    public function beforeSave($insert)
    {
        throw new NotSupportedException('Model is read-only');
    }

    public function beforeValidate()
    {
        throw new NotSupportedException('Model is read-only');
    }
}
