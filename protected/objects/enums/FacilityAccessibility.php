<?php
declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self unknown()
 */
class FacilityAccessibility extends Enum
{

    protected static function values(): array
    {
        return [
            'unknown' => 'unknown',
        ];
    }
}
