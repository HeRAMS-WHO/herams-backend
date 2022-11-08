<?php

declare(strict_types=1);

namespace prime\interfaces;

use herams\common\values\ResponseId;

interface FacilityForResponseCopy
{
    public function getLastResponseId(): ResponseId;
}
