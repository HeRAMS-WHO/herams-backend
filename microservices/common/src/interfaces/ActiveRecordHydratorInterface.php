<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\models\ActiveRecord;
use herams\common\models\RequestModel;

interface ActiveRecordHydratorInterface
{
    //It processes the data from a Request and put it into an ActiveRecord
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void;

    //It processes the data from an ActiveRecord and put it into a Request
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void;
}
