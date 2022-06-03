<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\controllers\page\Update;
use prime\objects\enums\Language;
use Traversable;
use WeakMap;

/**
 * Models a string that has localized values
 */
class LocalizedString
{
    /**
     * @var WeakMap<Language, string>
     */
    private readonly WeakMap $valueMap;

    /**
     * @param string|array<string,string> $value
     */
    public function __construct(string|array $value)
    {
        $this->valueMap = new WeakMap();
        if (is_string($value)) {
            $this->valueMap[Language::default()] = $value;
        } else {
            foreach ($value as $key => $localizedValue) {
                $this->valueMap[Language::from($key)] = $localizedValue;
            }
        }
        if (! isset($this->valueMap[Language::default()])) {
            throw new \InvalidArgumentException("A value for the default language is required");
        }
    }

    public function getDefault(): string
    {
        return $this->valueMap[Language::default()];
    }

    public function asArrayWithoutDefaultLanguage(): array
    {
        $result = [];
        /**
         * @var Language $language
         * @var string  $value
         */
        foreach ($this->valueMap as $language => $value) {
            if ($language !== Language::default()) {
                $result[$language->value] = $value;
            }
        }
        return $result;
    }
}
