<?php

declare(strict_types=1);

namespace prime\models\forms;

use Carbon\Carbon;
use prime\interfaces\HeramsResponseInterface;
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
use yii\validators\DefaultValueValidator;
use yii\validators\EachValidator;
use yii\validators\RangeValidator;

use function iter\all;
use function iter\apply;
use function iter\enumerate;
use function iter\filter;

/**
 * Class ResponseFilter implements filtering for Response Collections
 * @package prime\models\forms
 * @property ?Carbon $date
 */
class ResponseFilter extends Model
{
    /**
     * @var ?Carbon
     */
    private $date;
    public array|string $workspaceIds = [];

    public $advanced = [];

    /**
     * @var HeramsCodeMap
     */
    private $map;

    /**
     * @var QuestionInterface[]
     */
    private $advancedFilterMap = [];

    public function attributeLabels(): array
    {
        return [
            'date' => \Yii::t('app', 'Date'),
            'workspaceIds' => \Yii::t('app', 'Workspaces'),
        ];
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes[] = 'date';
        return $attributes;
    }


    public function setDate($date)
    {
        $this->date = empty($date) ? null : new Carbon($date);
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    public function __construct(
        ?SurveyInterface $survey,
        HeramsCodeMap $map,
        // TODO refactor this to be a an array of WorkspaceForFilter interface, but since most of this work is in the
        // HF layer it is skipped here.
        private $workspacesForFilter = [],
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
        usort($groups, function (GroupInterface $a, GroupInterface $b) {
            return $a->getIndex() <=> $b->getIndex();
        });
        foreach ($groups as $group) {
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
        foreach ($this->advancedFilterMap as $code => $question) {
            $rules[] = [
                ["adv_{$question->getTitle()}"],
                RangeValidator::class,
                'range' => array_map(function (AnswerInterface $answer) {
                    return $answer->getCode();
                }, $question->getAnswers())
            ];
        }
        $rules[] = ['workspaceIds', DefaultValueValidator::class, 'value' => []];
        $rules[] = ['workspaceIds', RangeValidator::class, 'range' => array_keys($this->workspacesForFilter), 'allowArray' => true];
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

    /**
     * Get the answer options (code => label) for a question identified by $fieldName
     * @param string $fieldName
     * @return array
     */
    public function advancedOptions(string $fieldName): array
    {
        $result = [];
        foreach ($this->advancedFilterMap[$fieldName]->getAnswers() as $answer) {
            $title = preg_split('/:\(/', $answer->getText())[0];
            if (is_string($title) && strpos($title, '/') !== false) {
                $parts = explode('/', $title, 2);
                $group = trim($parts[0]);
                if (isset($result[$group])) {
                    $result[$group][$answer->getCode()] = $title;
                } else {
                    $result[$group] = [
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

        return trim(html_entity_decode(
            preg_split('/:\(/', $this->advancedFilterMap[$code]->getText())[0]
        ));
    }

    public function getWorkspacesForFilters(): array
    {
        return $this->workspacesForFilter;
    }

    public function filterQuery(ActiveQuery $query): ActiveQuery
    {
        // Add filtering rules
        $query->andFilterWhere([
            '<=',
            'date',
            (string) $this->date
        ]);

        $query->andFilterWhere([
            '[[workspace_id]]' => $this->workspaceIds,
        ]);

        // Clone the primary query
        $sub = clone $query;
        $sub
            ->alias('sub')
            ->andWhere([
                '[[sub]].[[workspace_id]]' => new Expression("{$query->primaryTableName}.[[workspace_id]]"),
                '[[sub]].[[hf_id]]' => new Expression("{$query->primaryTableName}.[[hf_id]]"),
            ])->andWhere([
                'or',
                [
                    '>',
                    "[[sub]].[[date]]",
                    new Expression("{$query->primaryTableName}.[[date]]")
                ],
                [
                    'and',
                    [
                        '=',
                        "[[sub]].[[date]]",
                        new Expression("{$query->primaryTableName}.[[date]]")
                    ],
                    [
                        '>',
                        "[[sub]].[[id]]",
                        new Expression("{$query->primaryTableName}.[[id]]")
                    ],
                ]
            ]);

        $query->andWhere(['not exists', $sub]);

        foreach ($this->advanced as $key => $value) {
            if (!empty($value)) {
                $query->andWhere([
                    "json_unquote(json_extract([[data]],'$.{$key}'))" => $value
                ]);
            }
        }
        return $query;
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
            'workspaceIds' => $this->workspaceIds,
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
        if (isset($variables['workspaceIds'])) {
            $this->workspaceIds = $variables['workspaceIds'];
        }
        $this->advanced = $variables['advanced'] ?? [];
    }
}
