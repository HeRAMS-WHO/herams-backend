<?php

declare(strict_types=1);

namespace prime\objects;

use ResourceBundle;

/**
 * We implement the flyweight pattern, each locale only has 1 object instance.
 */
final class Locale
{
    private static array $objects;

    public readonly string $label;

    private function __construct(public readonly string $locale)
    {
        $label = locale_get_display_name($locale);
        if ($label === $locale) {
            throw new \Exception("Unsupported locale $locale");
        }
        $this->label = $label;
    }

    private static function init(): void
    {
        if (! isset(self::$objects)) {
            \Yii::beginProfile('locales');
            self::$objects = [];
            foreach (ResourceBundle::getLocales('') as $locale) {
                self::$objects[$locale] = new Locale($locale);
            }
            \Yii::endProfile('locales');
        }
    }

    public static function all(): array
    {
        self::init();
        return array_values(self::$objects);
    }

    public static function keys(): array
    {
        self::init();
        return array_keys(self::$objects);
    }

    /**
     * @param list<string> $locales
     */
    public static function fromValues(array $locales): iterable
    {
        self::init();
        foreach ($locales as $locale) {
            yield self::$objects[$locale];
        }
    }

    public static function from(string $value): self
    {
        self::init();
        if (! isset(self::$objects[$value])) {
            // Create it on the fly
            self::$objects[$value] = new self($value);
        }
        return self::$objects[$value];
    }

    public static function default(): self
    {
        self::init();
        return self::$objects['en'];
    }
}
