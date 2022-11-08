<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\common\enums\ConfigurationKey;
use herams\common\helpers\Locale;
use herams\common\models\Configuration;
use League\ISO3166\ISO3166;
use function iter\toArray;

class ConfigurationController extends Controller
{
    public function actionCountries(): array
    {
        return (new ISO3166())->all();
    }

    public function actionLocales(): array|string
    {
        $configEntry = Configuration::findOne([
            'key' => ConfigurationKey::Locales->value,
        ]);

        if (isset($configEntry) && is_array($configEntry->value)) {
            return toArray(Locale::fromValues($configEntry->value));
        }

        return [Locale::from("en")];
    }
}
