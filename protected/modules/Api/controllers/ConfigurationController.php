<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers;

use League\ISO3166\ISO3166;
use prime\models\ar\Configuration;
use prime\objects\enums\ConfigurationKey;
use prime\objects\Locale;
use function iter\toArray;

class ConfigurationController extends Controller
{

    public function actionCountries(): array
    {
        return (new ISO3166())->all();
    }

    public function actionLocales(): array|string
    {

        $configEntry = Configuration::findOne(['key' => ConfigurationKey::Locales->value]);

        if (isset($configEntry) && is_array($configEntry->value)) {
            return toArray(Locale::fromValues($configEntry->value));
        }

        return [Locale::from("en")];


    }

}
