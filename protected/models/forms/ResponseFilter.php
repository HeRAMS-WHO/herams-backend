<?php


namespace prime\models\forms;


use Carbon\Carbon;
use prime\objects\HeramsResponse;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Response;
use yii\base\Model;
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

    }

    public function rules()
    {
        return [
            [['date'], DateValidator::class, 'format' => 'php:Y-m-d'],
            [['locations', 'types'], SafeValidator::class],
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

        $allowedLocations = array_flip($this->locations);

        foreach($this->allResponses as $response) {
            // Date filter
            if ($response->getDate() === null || !$limit->greaterThan($response->getDate())) {
                continue;
            }

            // Type filter.
            if (!empty($this->types) &&
                !in_array($response->getType(), $this->types)
            ) {
                continue;
            }

            // Location filter
            if (!empty($allowedLocations) && !isset($allowedLocations[$response->getLocation()])) {
                continue;
            }

            $id = $response->getSubjectId();
            if (!isset($indexed[$id]) || $indexed[$id]->getDate() < $response->getDate()) {
                $indexed[$id] = $response;
            }


        }

        \Yii::endProfile('filter');
        return array_values($indexed);
    }

}