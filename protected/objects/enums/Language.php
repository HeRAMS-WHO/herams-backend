<?php
declare(strict_types=1);

namespace prime\objects\enums;

/**
 * @method static self enUS()
 * @method static self ar()
 * @method static self pt()
 * @method static self uk()
 * @method static self ku()
 * @method static self ps()
 * @method static self faAf()
 * @method static self nlNL()
 * @method static self frFR()
 * @method static self ka()
 * @method static self hy()
 */
class Language extends Enum
{
    /**
     * @codeCoverageIgnore
     */
    protected static function values(): \Closure
    {
        return static fn(string $method) => implode('-', str_split($method, 2));
    }

    /**
     * @codeCoverageIgnore
     */
    protected static function labels(): \Closure
    {
        return static fn(string $method): string => locale_get_display_name(implode('-', str_split($method, 2)));
    }
}
