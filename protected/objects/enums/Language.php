<?php
declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self enUS()
 * @method static self ar()
 * @method static self nlNL()
 * @method static self frFR()
 */
class Language extends Enum
{
    protected static function values(): \Closure
    {
        return static fn(string $method) => implode('-', str_split($method, 2));
    }

    protected static function labels(): \Closure
    {
        return static fn(string $method): string => locale_get_display_name(implode('-', str_split($method, 2))) ?: 'weird';
    }
}
