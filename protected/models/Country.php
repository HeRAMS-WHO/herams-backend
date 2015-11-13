<?php

namespace prime\models;

use prime\components\ActiveRecord;
use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\traits\JsonMemoryDataSourceTrait;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\LatLong;
use yii\base\Model;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
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

    private $_related = [];

    public function __get($name)
    {
        if (isset($this->_related[$name]) || array_key_exists($name, $this->_related)) {
            return $this->_related[$name];
        }
        $value = parent::__get($name);
        if ($value instanceof ActiveQueryInterface) {
            return $this->_related[$name] = $value->findFor($name, $this);
        } else {
            return $value;
        }
    }


    public function getLatLong()
    {
        return new LatLong(
            new Coordinate($this->latitude),
            new Coordinate($this->longitude)
        );
    }

    public function getProjects()
    {
        return Project::find()->joinWith('projectCountries')->andWhere([ProjectCountry::tableName() . '.country_iso_3' => $this->iso_3]);
    }

    public function getReports()
    {
        $result = Report::find()->joinWith(['project', 'project.projectCountries'])->andWhere([ProjectCountry::tableName() . '.country_iso_3' => $this->iso_3]);
        $result->multiple = true;
        return $result;
    }

    public function getReportsGroupedByTool()
    {
        return \yii\helpers\ArrayHelper::map(
            $this->reports,
            'id',
            function($model) {
                return $model;
            },
            function(\prime\models\ar\Report $model) {
                return $model->project->tool_id;
            }
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