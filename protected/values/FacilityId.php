<?php

declare(strict_types=1);

namespace prime\values;

use prime\models\ar\Facility;

/**
 * TODO Limesurvey deprecation: must extend IntId
 */
class FacilityId extends StringId
{
    public static function fromFacility(Facility $facility): static
    {
        if (! isset($facility->id)) {
            throw new \InvalidArgumentException('Facility must have an id');
        }
        return new self((string) $facility->id);
    }
}
