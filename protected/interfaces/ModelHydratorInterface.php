<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\models\ActiveRecord;
use yii\base\Model;

interface ModelHydratorInterface
{
    public function hydrateFromActiveRecord(ActiveRecord $source, Model $target): void;
}
