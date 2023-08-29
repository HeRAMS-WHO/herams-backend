<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\enums\ProjectVisibility;
use League\ISO3166\ISO3166;
use function iter\filter;

class ConfigurationProvider
{
    public const COUNTRY_UNSPECIFIED = 'XXX';

    /**
     * @return list<Locale>
     */
    public function getPlatformLocales(array $locales = ["en", "fr", "ar", "es", "ru", "zh"]): array
    {
        return Locale::fromValues($locales);
    }

    /**
     * Checks if a given locale is valid against a list.
     */
    private function isValidLocale(?string $locale, array $validLocales): bool
    {
        return in_array($locale, $validLocales, true);
    }

    public function getLocalizedLanguageNames($lang = null, array $locales = ["en", "fr", "ar", "ru", "zh"]): array
    {
        // First, check if the userLocale is valid or not
        if (is_null($lang) || ! $this->isValidLocale($lang, $locales)) {
            // Get the user's current locale
            $lang = Locale::from(\Yii::$app->language ?? 'en');
        }

        $userLocale = Locale::from($lang);

        // Get a list of locales you want to fetch
        $locales = $this->getPlatformLocales($locales);

        // Return the localized names of these locales
        $languageNames = [];
        foreach ($locales as $locale) {
            $localizedArray = $locale->toLocalizedArray($userLocale);
            $languageNames[$localizedArray['locale']] = $localizedArray['label'];
        }

        return $languageNames;
    }

    /**
     * @return non-empty-list<array{name: string, alpha2: string, alhpa3: string}>
     */
    public function getPlatformCountries(): array
    {
        return [
            [
                'alpha3' => self::COUNTRY_UNSPECIFIED,
                'name' => \Yii::t('app', "Unspecified (training / not in list)"),
            ],
            ...filter(fn (array $country) => [
                'alpha3' => $country['alpha3'],
                'name' => $country['name'],

            ], (new ISO3166())->all()),

        ];
    }

    /**
     * @return list<ProjectVisibility>
     */
    public function getPlatformVisibilities(): array
    {
        return ProjectVisibility::toArray();
    }
}
