<?php
declare(strict_types=1);

namespace prime\models\facility;

use prime\values\ResponseId;

class FacilityForResponseCopy implements \prime\interfaces\FacilityForResponseCopy
{
    public function __construct(private ResponseId $lastResponseId)
    {
    }


    public function getLastResponseId(): ResponseId
    {
        return $this->lastResponseId;
    }
}
