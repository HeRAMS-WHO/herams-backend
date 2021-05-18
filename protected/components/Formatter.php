<?php
declare(strict_types=1);

namespace prime\components;


use Ramsey\Uuid\Uuid;

class Formatter extends \yii\i18n\Formatter
{

    public function asUuid($value)
    {
        return Uuid::fromBytes($value);
    }
}
