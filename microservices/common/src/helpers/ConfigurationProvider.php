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
    public function getPlatformLocales(): array
    {
        return Locale::fromValues(["en", "fr", "ar", "es", "ru", "zh"]);
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
