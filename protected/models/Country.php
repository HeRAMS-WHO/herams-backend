<?php

namespace prime\models;

use prime\components\ActiveRecord;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class Country extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'longitude', 'latitude'], RequiredValidator::class],
            [['name'], StringValidator::class],
            [['longitude', 'latitude'], NumberValidator::class]
        ];
    }

    public function scenarios()
    {
        return [
            'update' => ['name', 'longitude', 'latitude']
        ];
    }
}