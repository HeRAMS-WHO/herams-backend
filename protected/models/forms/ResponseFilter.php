<?php


namespace prime\models\forms;


use Carbon\Carbon;
use function iter\all;
use function iter\apply;
use function iter\enumerate;
use function iter\filter;
use function iter\map;
use prime\objects\HeramsResponse;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Response;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

/**
 * Class ResponseFilter implements filtering for Response Collections
 * @package prime\models\forms
 */
class ResponseFilter extends Model
{
    public $date;

    public $types;
    public $locations = [];
    public $advanced = [];

    /**
     * @var HeramsResponse[]
     */
    private $allResponses;

    /**
     * @param HeramsResponse[] $responses
     */
    public function __construct(
        array $responses,
        SurveyInterface $survey
    ) {
        parent::__construct([]);
        $this->allResponses = $responses;
        $this->date = date('Y-m-d');

    }

    public function rules()
    {
        return [
            [['date'], DateValidator::class, 'format' => 'php:Y-m-d'],
            [['locations', 'types', 'advanced'], SafeValidator::class],
        ];
    }


    /**
     * @param Response[]
     * @return array
     */
    public function nestedLocationOptions(): array
    {
        $locations = [];
        /** @var HeramsResponse $response */
        foreach($this->allResponses as $response) {
            $location = array_filter([
                $response->getValueForCode('GEO1'),
                $response->getValueForCode('GEO2')
            ]);
            if (count($location) === 2) {
                $locations[$location[0]][$location[1]] = $location[1];
            }

        }
        return $locations;
    }

    public function filter(): array
    {

        \Yii::beginProfile('filter');
        $limit = new Carbon($this->date);
        // Index by UOID.
        /** @var HeramsResponse[] $indexed */
        $indexed = [];
        /** @var HeramsResponse $response */



        apply(function($response) use (&$indexed) {
            $id = $response->getSubjectId();
            if (!isset($indexed[$id]) || $indexed[$id]->getDate() < $response->getDate()) {
                $indexed[$id] = $response;
            }
        }, filter(function(HeramsResponse $response) use ($limit) {
            // Date filter
            if ($response->getDate() === null || !$limit->greaterThan($response->getDate())) {
                return false;
            }

            // Type filter.
            if (!empty($this->types) && !in_array($response->getType(), $this->types)
            ) {
                return false;
            }

            // Location filter
            if (!empty($this->locations) && !isset(array_flip($this->locations)[$response->getLocation()])) {
                return false;
            }

            // Advanced filter.
            if (!all(function(array $pair) use ($response) {
                list($key, $allowedValues) = $pair;
                // Ignore empty filters.
                if (empty($allowedValues)) return true;
                $chosenValue = $response->getValueForCode($key);
                $chosenValues = is_array($chosenValue) ? $chosenValue : [$chosenValue];
                // We need overlap.
                return !empty(array_intersect($allowedValues, $chosenValues));
            }, enumerate($this->advanced))) {
                return false;
            }

            return true;

        }, $this->allResponses));


        \Yii::endProfile('filter');
        return array_values($indexed);
    }

    public function formName()
    {
        return 'RF';
    }


}