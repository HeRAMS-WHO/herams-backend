<?php
declare(strict_types=1);

namespace prime\objects\enums;

class ProjectStatus
{
    const ONGOING = 0;
    const BASELINE = 1;
    const TARGET = 2;
    const EMERGENCY = 3;

    /**
     * @codeCoverageIgnore
     * @param int $statusCode
     * @return string
     */
    public static function label(int $statusCode): string
    {
        return self::toArray()[$statusCode] ?? '';
    }

    /**
     * @codeCoverageIgnore
     * @return string[]
     */
    public static function toArray(): array
    {
        return [
            self::ONGOING => 'Ongoing',
            self::BASELINE => 'Baseline',
            self::TARGET => 'Target',
            self::EMERGENCY => 'Emergency specific'
        ];
    }
}
