<?php

declare(strict_types=1);

namespace prime\traits;

use yii\base\NotSupportedException;

trait DisableYiiLoad
{
    final public function load($data, $formName = null): void
    {
        throw new NotSupportedException('Use a model hydrator instead');
    }
}
