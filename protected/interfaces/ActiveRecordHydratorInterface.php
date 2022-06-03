<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\models\ActiveRecord;
use yii\base\Model;

interface ActiveRecordHydratorInterface
{
    public function hydrateActiveRecord(Model $source, ActiveRecord $target): void;
}
