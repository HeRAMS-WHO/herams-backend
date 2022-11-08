<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\models\ActiveRecord;
use herams\common\models\RequestModel;

interface ActiveRecordHydratorInterface
{
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void;

    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void;
}
