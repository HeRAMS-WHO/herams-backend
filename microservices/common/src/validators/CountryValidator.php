<?php

declare(strict_types=1);

namespace herams\common\validators;

use herams\common\helpers\ConfigurationProvider;
use yii\validators\Validator;

class CountryValidator extends Validator
{
    protected function validateValue($value): array|null
    {
        if (! is_string($value)) {
            return ['Value is not an array', []];
        }

        $configurationProvider = new ConfigurationProvider();

        foreach ($configurationProvider->getPlatformCountries() as $country) {
            if ($value === $country['alpha3']) {
                return null;
            }
        }
        return [
            \Yii::t('app.validator.country', 'Country code {value} is not valid'), [
                'value' => $value,

            ], ];
    }
}
