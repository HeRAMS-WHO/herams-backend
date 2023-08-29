<?php

declare(strict_types=1);

namespace herams\common\domain\accessRequest;

use herams\common\models\AccessRequest;

final class AccessRequestRepository
{
    public function deleteAll(array $condition): void
    {
        AccessRequest::deleteAll($condition);
    }
}
