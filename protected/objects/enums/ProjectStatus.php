<?php
declare(strict_types=1);

namespace prime\objects\enums;

use Spatie\Enum\Enum;

/**
 * @method static self ongoing()
 * @method static self baseline()
 * @method static self target()
 * @method static self emergency()
 */
class ProjectStatus extends Enum
{
    protected static function values()
    {
        return [
            'ongoing' => 0,
            'baseline' => 1,
            'target' => 2,
            'emergency' => 3
        ];
    }

    protected static function labels()
    {
        return [
            'ongoing' => 'Ongoing',
            'baseline' => 'Baseline',
            'target' => 'Target',
            'emergency' => 'Emergency specific'
        ];
    }
}
