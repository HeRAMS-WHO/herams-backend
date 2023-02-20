<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\enums\ConfigurationKey;
use herams\common\models\Configuration;
use herams\common\values\SurveyId;

class ConfigurationProvider
{
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

}
