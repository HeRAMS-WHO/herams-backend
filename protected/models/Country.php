<?php

namespace prime\models;

use prime\models\ActiveRecord;
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
    public $region;

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

    public static function findAllInRegions($regions = []) {
        $regions = array_flip($regions);
        $result = [];
        foreach(self::findAll() as $country) {
            if(isset($regions[$country->region])) {
                $result[] = $country;
            }
        }
        return $result;
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
        return Project::find()->andWhere([Project::tableName() . '.country_iso_3' => $this->iso_3]);
    }

    public static function regionOptions()
    {
        $result = [];
        /** @var Country $country */
        foreach(self::findAll() as $country) {
            $result[$country->region] = $country->region;
        }

        return $result;
    }

    public function getRegionName()
    {
        return (isset($this->regionOptions()[$this->region]) ? $this->regionOptions()[$this->region] : $this->region);
    }

    public function getReports()
    {
        $result = Report::find()->joinWith(['project'])->andWhere([Project::tableName() . '.country_iso_3' => $this->iso_3]);
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
     * @todo refactor to use settings
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
                'iso_3' => 'properties.ISO_3_CODE',
                'region' => 'properties.WHO_REGION'
            ]
        ];
    }

    /**
     * To allow sorting by country (countries are not in the database) we construct a case statement.
     * This is not ideal from a database perspective, but for this case (there won't be many projects), it's ok.
     */
    public static function searchCaseStatement($attribute) {
        $countries = static::findAll();
        ArrayHelper::multisort($countries, 'name');
        $case = '(case ';
        foreach ($countries as $key => $value) {
            $case .= "when country_iso_3 = '{$value->iso_3}' then $key ";
        }
        $case .= ' end)';
        return $case;
    }
}