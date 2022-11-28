<?php

declare(strict_types=1);

namespace herams\common\values;

use herams\common\domain\facility\Facility;

class FacilityId extends IntegerId
{
    public static function fromFacility(Facility $facility): static
    {
        if (! isset($facility->id)) {
            throw new \InvalidArgumentException('Facility must have an id');
        }
        return new self($facility->id);
    }
}
