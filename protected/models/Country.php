<?php

namespace prime\models;

use prime\components\ActiveRecord;
use prime\traits\JsonMemoryDataSourceTrait;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\LatLong;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Class Country
 * @package prime\models
 */
class Country extends Model
{
    use JsonMemoryDataSourceTrait;

    public $name;
    public $iso_3;
    public $latitude;
    public $longitude;

    public function getLatLong()
    {
        return new LatLong(
            new Coordinate($this->latitude),
            new Coordinate($this->longitude)
        );
    }

    /**
     * @return array
     * [
     *  'file' => (string) full file path,
     *  'keyPath' => (string) the path to the key inside a row
     *  'dataPath' => (string, optional) the path to the rows in the data,
     *  'attributeMap' => (array, optional) map from attribute name to attribute path in row
     * ]
     */
    protected static function getSource()
    {
        return [
            'file' => '@app/data/countryCentroids/2015-11-12_12-00-00.json',
            'keyPath' => 'properties.ISO_3_CODE',
            'dataPath' => 'features',
            'attributeMap' => [
                'latitude' => 'geometry.coordinates.1',
                'longitude' => 'geometry.coordinates.0',
                'name' => 'properties.CNTRY_TERR',
                'iso_3' => 'properties.ISO_3_CODE'
            ]
        ];
    }
}