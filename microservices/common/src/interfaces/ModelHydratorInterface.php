<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\models\ActiveRecord;
use yii\base\Model;

interface ModelHydratorInterface
{
    public function hydrateFromActiveRecord(ActiveRecord $source, Model $target): void;
}
