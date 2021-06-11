<?php
declare(strict_types=1);

namespace prime\interfaces;

use prime\values\ResponseId;

interface FacilityForResponseCopy
{
    public function getLastResponseId(): ResponseId;
}
