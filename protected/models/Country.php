<?php

namespace prime\models;

use prime\components\ActiveRecord;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\LatLong;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Class Country
 * @package prime\models
 * @property float $latitude
 * @property float $longitude
 */
class Country extends ActiveRecord
{
    public function getLatLong()
    {
        return new LatLong(
            new Coordinate($this->latitude),
            new Coordinate($this->longitude)
        );
    }

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