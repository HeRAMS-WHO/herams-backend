<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\models\ActiveRecord;
use yii\base\Model;

interface ActiveRecordHydratorInterface
{
    public function hydrateActiveRecord(\prime\models\RequestModel $source, ActiveRecord $target): void;

    public function hydrateRequestModel(ActiveRecord $source, \prime\models\RequestModel $target): void;
}
