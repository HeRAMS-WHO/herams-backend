<?php
declare(strict_types=1);

namespace prime\models\forms;


use Carbon\Carbon;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Response;
use prime\objects\HeramsCodeMap;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\StringHelper;
use yii\validators\DateValidator;
use yii\validators\RangeValidator;
use function iter\all;
use function iter\apply;
use function iter\enumerate;
use function iter\filter;

/**
 * Class ResponseFilter implements filtering for Response Collections
 * @package prime\models\forms
 * @property Carbon $date
 */
class ResponseFilter extends Model
{
    /**
     * @var ?Carbon
     */
    private $date;

    public $advanced = [];

    /**
     * @var HeramsCodeMap
     */
    private $map;

    /**
     * @var QuestionInterface[]
     */
    private $advancedFilterMap = [];

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes[] = 'date';
        return $attributes;
    }


    public function setDate($date)
    {
        $this->date = empty($date) ? null: new Carbon($date);
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    public function __construct(
        ?SurveyInterface $survey,
        HeramsCodeMap $map
    ) {
        parent::__construct([]);
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
        } elseif (empty($value)) {
            unset($this->advanced[substr($name, 4)]);
        } else {
            $this->advanced[substr($name, 4)] = $value;
        }

    }

    public function advancedOptions(string $fieldName): array
    {
        $result = [];
        foreach($this->advancedFilterMap[$fieldName]->getAnswers() as $answer) {
            $title = strtok(strip_tags($answer->getText()), ':(');
            if (is_string($title) && strpos($title, '/') !== false) {
                $parts = explode('/', $title, 2);
                if (isset($result[$parts[0]])) {
                    $result[$parts[0]][$answer->getCode()] = $title;
                } else {
                    $result[$parts[0]] = [
                        $answer->getCode() => $title
                    ];
                }
            } else {
                $result[$answer->getCode()] = $title;
            }
        }
        return $result;
    }

    public function getAttributeLabel($attribute)
    {
        if (strncmp($attribute, 'adv_', 4) !== 0) {
            return parent::getAttributeLabel($attribute);
        }
        $code = substr($attribute, 4);

        return trim(html_entity_decode(strtok(strip_tags($this->advancedFilterMap[$code]->getText()), ':(')));

    }

    public function filterQuery(ActiveQuery $query): ActiveQuery
    {
        // Find the latest response per HF.
        $left = clone $query;
        $left->alias('left');
        $right = Response::find()
            ->andFilterWhere([
                '<=',
                'date',
                $this->date
            ]);
        $left->leftJoin(['right' => $right], [
            'and',
            [
                '[[left]].[[workspace_id]]' => new Expression('[[right]].[[workspace_id]]'),
                '[[left]].[[hf_id]]' => new Expression('[[right]].[[hf_id]]'),
            ],
            [
                '<',
                "[[left]].[[date]]",
                new Expression("[[right]].[[date]]")
            ],

        ])->groupBy([
            '[[left]].[[workspace_id]]',
            '[[left]].[[hf_id]]'
        ])->select([
            'id' => "max([[left]].[[id]])"
        ])->andWhere([
            '[[right]].[[id]]' => null
        ])->andFilterWhere([
            '<=',
            '[[left]].[[date]]',
            $this->date
        ]);


        $query->andWhere([
            'id' => $left
        ]);

        foreach($this->advanced as $key => $value) {
            if (!empty($value)) {
                $query->andWhere([
                    "json_unquote(json_extract([[data]],'$.{$key}'))" => $value
                ]);
            }
        }
        return $query;
    }

    public function filter(iterable $responses): iterable
    {
        \Yii::beginProfile('filter');
        // Index by UOID.
        /** @var HeramsResponseInterface[] $indexed */
        $indexed = [];

                apply(function(HeramsResponseInterface $response) use (&$indexed) {
            $id = $response->getSubjectId();
            if (!isset($indexed[$id])
                || $indexed[$id]->getDate()->lessThan($response->getDate())
                || ($indexed[$id]->getDate()->equalTo($response->getDate()) && $indexed[$id]->getId() < $response->getId())

            ) {
                $indexed[$id] = $response;
            }
        }, filter(function(HeramsResponseInterface $response) {
            // Date filter
            if (isset($this->date) && !$this->date->greaterThanOrEqualTo($response->getDate())) {
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

        }, $responses));

        \Yii::endProfile('filter');
        return array_values($indexed);
    }

    public function formName()
    {
        return 'RF';
    }

    public function toQueryParam(): string
    {
        $data = gzcompress(json_encode(array_filter([
            'advanced' => $this->advanced,
            'date' => $this->date,
        ])), 9);
        $length = mb_strlen($data, '8BIT');
        return StringHelper::base64UrlEncode($data . str_repeat("\0", (2 * ($length % 3)) % 3));
    }

    public function fromQueryParam(string $value)
    {
        $variables = json_decode(gzuncompress(StringHelper::base64UrlDecode($value)), true);
        if (isset($variables['date'])) {
            $this->setDate($variables['date']);
        }
        $this->advanced = $variables['advanced'] ?? [];
    }
}