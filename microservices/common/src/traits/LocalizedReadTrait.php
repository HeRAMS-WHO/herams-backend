<?php

declare(strict_types=1);

namespace herams\common\traits;

trait LocalizedReadTrait
{
    abstract public function getAttribute($name);

    /**
     * @param string ...$locales The locales in order of preference
     * @return string
     */
    public function getLocalizedAttribute(string $attribute, string ...$locales): null|string
    {
        $values = $this->getAttribute('i18n')[$attribute] ?? [];
        if (empty($values)) {
            return null;
        }
        foreach ($locales as $locale) {
            if (isset($values[$locale])) {
                return $values[$locale];
            }
        }
        return reset($values);
    }
}
