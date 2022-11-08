<?php

declare(strict_types=1);

namespace prime\models\facility;

use herams\common\values\ResponseId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
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
