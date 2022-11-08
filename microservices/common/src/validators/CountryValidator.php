<?php

declare(strict_types=1);

namespace herams\common\validators;

use League\ISO3166\ISO3166;
use yii\validators\Validator;

class CountryValidator extends Validator
{
    protected function validateValue($value): array|null
    {
        if (! is_string($value)) {
            return ['Value is not an array', []];
        }

        $data = new ISO3166();
        try {
            $data->alpha3($value);
        } catch (\Throwable $t) {
            return [
                \Yii::t('app.validator.country', 'Country code {value} is not valid'), [
                    'value' => $value,

                ], ];
        }
        return null;
    }
}
