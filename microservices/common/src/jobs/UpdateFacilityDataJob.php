<?php

declare(strict_types=1);

namespace herams\common\jobs;

use herams\common\values\FacilityId;

final class UpdateFacilityDataJob implements \JsonSerializable
{
    public readonly FacilityId $facilityId;

    public function __construct(FacilityId|string|int $facilityId)
    {
        $this->facilityId = $facilityId instanceof FacilityId ? $facilityId : new FacilityId($facilityId);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'facilityId' => $this->facilityId->getValue(),
        ];
    }
}
