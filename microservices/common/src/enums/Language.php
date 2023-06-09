<?php

declare(strict_types=1);

namespace herams\common\enums;

/**
 * @deprecated use Locale
 */
enum Language: string
{
    case en = 'en';
    //    case enUS = 'en-US';
    case arAR = 'ar-AR';
    case ar = 'ar';
    case fr = 'fr';
    case frFR = 'fr-FR';

    public static function default(): self
    {
        return self::en;
    }

    public function label(null|Language $displayLocale = null): string
    {
        return locale_get_display_name($this->value, $displayLocale?->value);
    }

    public static function toLocalizedArray(null|Language $displayLocale = null): array
    {
        $result = [];
        foreach (self::cases() as $language) {
            $result[$language->value] = $language->label($displayLocale);
        }
        return $result;
    }

    public static function toLocalizedArrayWithoutSourceLanguage(null|Language $displayLocale = null): array
    {
        $result = [];
        foreach (self::cases() as $language) {
            if ($language !== \Yii::$app->sourceLanguage) {
                $result[$language->value] = $language->label($displayLocale);
            }
        }
        return $result;
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        $values = [];
        foreach (self::cases() as $language) {
            $values[] = $language->value;
        }
        return $values;
    }
}
