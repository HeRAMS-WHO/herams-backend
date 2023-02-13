<?php

declare(strict_types=1);

namespace herams\common\helpers;

use WeakMap;

/**
 * Models a string that has localized values
 */
class LocalizedString implements \JsonSerializable, \Stringable
{
    /**
     * @var WeakMap<Locale, string>
     */
    private readonly WeakMap $valueMap;

    /**
     * @param string|array<string,string> $value
     */
    public function __construct(string|array $value)
    {
        $this->valueMap = new WeakMap();
        if (is_string($value)) {
            $this->valueMap->offsetSet(Locale::default(), trim($value));
        } else {
            foreach ($value as $key => $localizedValue) {
                $this->valueMap->offsetSet(Locale::from($key), trim($localizedValue));
            }
        }
    }

    public function getDefault(): string
    {
        return $this->valueMap[Locale::default()];
    }

    public function getFor(Locale $locale): string
    {
        return $this->valueMap[$locale] ?? $this->getDefault();
    }

    public function asArrayWithoutDefaultLanguage(): array
    {
        $result = [];
        /**
         * @var Locale $locale
         * @var string  $value
         */
        foreach ($this->valueMap as $locale => $value) {
            if ($locale !== Locale::default()) {
                $result[$locale->locale] = $value;
            }
        }
        return $result;
    }

    /**
     * @return array<string, string>
     */
    public function asDictionary(): array
    {
        $result = [];
        /**
         * @var Locale $locale
         * @var string  $value
         */
        foreach ($this->valueMap as $locale => $value) {
            $result[$locale->locale] = $value;
        }
        return $result;
    }

    public function jsonSerialize(): mixed
    {
        return $this->asDictionary();
    }

    public function __toString()
    {
        return $this->getDefault();
    }
}
