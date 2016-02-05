<?php

namespace prime\models\forms;

use app\queries\ProjectQuery;
use Befound\Components\DateTime;
use Carbon\Carbon;
use prime\models\ar\Setting;
use prime\models\Country;
use prime\models\mapLayers\HealthClusters;
use prime\objects\ResponseFilter;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;
use yii\validators\RangeValidator;

class MarketplaceFilter extends Model{

    const DATE_FORMAT_PHP = 'd-m-Y';
    const DATE_FORMAT_JS = 'dd-mm-yyyy';

    public $countries;
    public $regions;
    public $endDate;
    public $structures;

    /**
     * @param ResponseInterface[] $responses
     * @return ResponseInterface[]
     */
    public function applyToResponses(array $responses)
    {
        $filter = new ResponseFilter($responses);
        $countries = array_flip($this->countries);
        $structures = array_flip($this->structures);
        $filter->filter(function($response) use ($countries, $structures) {
            /** @var ResponseInterface $response */
            $result = true;
            //check if the country of the response matches the filter
            $result = $result && isset($countries[$response->getData()['PRIMEID']]);
            //check if the submitdate of the response matches the filter
            if($response->getSurveyId() == Setting::get('countryGradesSurvey') || $response->getSurveyId() == Setting::get('eventGradesSurvey')) {
                $result = $result && (new Carbon($response->getData()['GM01']))->lte(new Carbon($this->endDate));
            } elseif ($response->getSurveyId() == Setting::get('healthClusterMappingSurvey')) {
                $result = $result && (new Carbon($response->getData()['CM03']))->lte(new Carbon($this->endDate));
            } else {
                $result = $result && false;
            }
            //check if the structure of the response matches the filter
            if ($response->getSurveyId() == Setting::get('healthClusterMappingSurvey')) {
                $result = $result && isset($structures[$response->getData()['CM00']]);
            }
            return $result;
        });
        return $filter->getFilteredResponses();
    }

    public function applyToProjects(ProjectQuery $query)
    {
        return $query->andWhere(
            [
                'and',
                ['country_iso_3' => $this->countries],
                ['<=', 'created', (new Carbon($this->endDate))->format(DateTime::MYSQL_DATETIME)],
            ]
        )
            ->andWhere(
            [
                'or',
                ['closed' => null],
                ['>=', 'closed', (new Carbon($this->endDate))->format(DateTime::MYSQL_DATE)]
            ]
        );
    }

    public function init()
    {
        parent::init();
        $this->endDate = (new Carbon())->format(self::DATE_FORMAT_PHP);
        $this->regions = array_keys($this->regionOptions());
        $this->structures = array_keys($this->structureOptions());
        $this->countries = ArrayHelper::getColumn(Country::findAll(), 'iso_3');
    }

    public function load($data, $formName = null)
    {
        $this->countries = [];
        $result = parent::load($data, $formName);
        if($this->validate(['regions'])) {
            $this->countries = array_unique(
                ArrayHelper::merge(
                    $this->countries,
                    ArrayHelper::getColumn(Country::findAllInRegions($this->regions), 'iso_3')
                )
            );
        }
        return $result;
    }

    public function regionOptions()
    {
        return Country::regionOptions();
    }

    public function rules()
    {
        return [
            [['regions'], RangeValidator::class, 'range' => array_keys(self::regionOptions()), 'allowArray' => true],
            [['countries'], RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3'), 'allowArray' => true],
            [['endDate'], DateValidator::class,'format' => self::DATE_FORMAT_JS],
            [['structures'], RangeValidator::class, 'range' => array_keys(self::structureOptions()), 'allowArray' => true]
        ];
    }

    public function structureOptions()
    {
        return HealthClusters::structureMap();
    }



}