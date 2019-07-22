<?php


namespace prime\models\forms;


use Carbon\Carbon;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsResponse;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Model;
use yii\validators\DateValidator;
use yii\validators\RangeValidator;
use function iter\all;
use function iter\apply;
use function iter\enumerate;
use function iter\filter;

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
     * @var HeramsCodeMap
     */
    private $map;

    /**
     * @var QuestionInterface[]
     */
    private $advancedFilterMap = [];
    /**
     * @param HeramsResponse[] $responses
     */
    public function __construct(
        array $responses,
        ?SurveyInterface $survey,
        HeramsCodeMap $map
    ) {
        parent::__construct([]);
        $this->allResponses = $responses;
        $this->date = date('Y-m-d');
        $this->map = $map;

        if (isset($survey)) {
            $this->initAdvancedFilterMap($survey);
        }
    }
    private function initAdvancedFilterMap(SurveyInterface $survey)
    {
        $groups = $survey->getGroups();
        usort($groups, function(GroupInterface $a, GroupInterface $b) {
            return $a->getIndex() <=> $b->getIndex();
        });
        foreach($groups as $group) {
            foreach ($group->getQuestions() as $question) {
                if (null !== $answers = $question->getAnswers()) {
                    $this->advancedFilterMap[$question->getTitle()] = $question;
                }
            }
        }
    }

    public function rules()
    {
        $rules = [];
        $rules[] = [['date'], DateValidator::class, 'format' => 'php:Y-m-d'];
//        $rules[] = [['locations'], RangeValidator::class, 'range' => array_values($this->nestedLocationOptions()), 'allowArray' => true];
//        $rules[] = [['types'], RangeValidator::class, 'range' => array_values($this->typeOptions()), 'allowArray' => true];
        foreach($this->advancedFilterMap as $code => $question) {
            $rules[] = [
                ["adv_{$question->getTitle()}"],
                RangeValidator::class,
                'range' => array_map(function(AnswerInterface $answer) { return $answer->getCode(); }, $question->getAnswers())
            ];
        }
        return $rules;
    }

    public function __get($name)
    {
        if (strncmp($name, 'adv_', 4) !== 0) {
            return parent::__get($name);
        }
        return $this->advanced[substr($name, 4)] ?? null;
    }

    public function __set($name, $value)
    {
        if (strncmp($name, 'adv_', 4) !== 0) {
            parent::__set($name, $value);
        } else {
            $this->advanced[substr($name, 4)] = $value;
        }

    }

    public function advancedOptions(string $fieldName): array
    {
        $result = [];
        foreach($this->advancedFilterMap[$fieldName]->getAnswers() as $answer) {
            $result[$answer->getCode()] = explode(':', strip_tags($answer->getText()), 2)[0];
        }
        return $result;
    }

    public function getAttributeLabel($attribute)
    {
        if (strncmp($attribute, 'adv_', 4) !== 0) {
            return parent::getAttributeLabel($attribute);
        }
        $code = substr($attribute, 4);

        return trim(html_entity_decode(explode(':', strip_tags($this->advancedFilterMap[$code]->getText()), 2)[0]));

    }


    public function filter(): array
    {
        \Yii::beginProfile('filter');
        $limit = new Carbon($this->date);
        // Index by UOID.
        /** @var HeramsResponse[] $indexed */
        $indexed = [];

        $locations = [];
        foreach((array) $this->locations as $location) {
            foreach(explode(',', $location) as $option) {
                $locations[$option] = true;
            }
        }
        apply(function(HeramsResponse $response) use (&$indexed) {
            $id = $response->getSubjectId();
            if (!isset($indexed[$id])
                || $indexed[$id]->getDate()->lessThan($response->getDate())
                || ($indexed[$id]->getDate()->equalTo($response->getDate()) && $indexed[$id]->getId() < $response->getId())

            ) {
                $indexed[$id] = $response;
            }
        }, filter(function(HeramsResponse $response) use ($limit, $locations) {
            // Date filter
            if (!$limit->greaterThanOrEqualTo($response->getDate())) {
                return false;
            }

            // Type filter.
            if (!empty($this->types) && !in_array($response->getType(), $this->types)
            ) {
                return false;
            }

            // Location filter
            if (!empty($locations) && !isset($locations[$response->getLocation()])) {
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