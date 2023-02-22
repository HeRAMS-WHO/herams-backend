<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\enums\ConfigurationKey;
use herams\common\models\Configuration;
use herams\common\values\SurveyId;
use League\ISO3166\ISO3166;
use function iter\filter;
use function iter\toArray;

class ConfigurationProvider
{
    public const COUNTRY_UNSPECIFIED = 'XXX';
    /**
     * @return list<Locale>
     */
    public function getPlatformLocales(): array
    {
        $data = Configuration::find()->andWhere([
            'key' => ConfigurationKey::Locales->value,
        ])->one();

        return Locale::fromValues($data?->value ?? ["en", "fr", "ar", "es", "ru", "zh"]);
    }

    /**
     * @return non-empty-list<array{name: string, alpha2: string, alhpa3: string}>
     */
    public function getPlatformCountries(): array
    {
        return [
            [
                'alpha3' => self::COUNTRY_UNSPECIFIED,
                'name' => \Yii::t('app', "Unspecified (training / not in list)")
            ],
            ...filter(fn(array $country) => [
                'alpha3' => $country['alpha3'],
                'name' => $country['name']

            ], (new ISO3166())->all())

        ];

    }

}
