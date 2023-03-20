<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\interfaces\LocalizableInterface;
use ResourceBundle;

/**
 * We implement the flyweight pattern, each locale only has 1 object instance.
 */
final class Locale implements LocalizableInterface
{
    /**
     * @var array<string, self>
     */
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
     * @return list<self>
     */
    public static function fromValues(array $locales): array
    {
        self::init();
        $result = [];
        foreach ($locales as $locale) {
            $result[] = self::$objects[$locale];
        }
        return $result;
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

    public function toLocalizedArray(Locale $locale): array
    {
        return [
            'label'=> ucfirst(locale_get_display_name($this->locale, $locale->locale)),
            'locale'=> $this->locale
        ];
    }
}
