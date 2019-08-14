<?php


namespace prime\models\ar;


use prime\interfaces\PageInterface;
use prime\models\ActiveRecord;
use prime\models\ar\elements\BarChart;
use prime\models\ar\elements\Chart;
use prime\models\ar\elements\Map;
use prime\models\ar\elements\Table;
use prime\objects\HeramsResponse;
use prime\widgets\element\Element as ElementWidget;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\NotSupportedException;
use yii\helpers\Json;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

/**
 *
 * @property boolean $transpose
 * @property array $config
 * @property Page $page
 * @property Project $project
 */
class Element extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%element}}';
    }

    public static function instantiate($row)
    {
        if (!isset($row['type'])) {
            throw new \InvalidArgumentException('Type must be set');
        }

        switch ($row['type']) {
            case 'map': return new Map();
            case 'chart': return new Chart();
            case 'barchart': return new BarChart();
            case 'table': return new Table();
            default:
                throw new \InvalidArgumentException('Unknown type: ' . $row['type']);
        }
    }

    protected function getWidgetInternal(SurveyInterface $survey, iterable $data)
    {
        throw new NotSupportedException('This must be implemented in a subclass');
    }

    final public function getWidget(SurveyInterface $survey, iterable $data, PageInterface $page): ElementWidget
    {
        return $this->getWidgetInternal($survey, $page->filterResponses($this->prepareData($data)));
    }

    protected function findQuestionByCode(SurveyInterface $survey, string $text): ?QuestionInterface
    {
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if ($question->getTitle() === $text) {
                    return $question;
                }

            }
        }
    }

    public function getCode(): ?string
    {
        return $this->config['code'] ?? null;
    }

    public function setCode(string $value): void
    {
        $config = $this->config;
        $config['code'] = $value;
        $this->config = $config;
    }

    public function getColors(): array
    {
        return $this->config['colors'] ?? [];
    }

    public function setColors(array $value): void
    {
        $config = $this->config;
        $config['colors'] = $value;
        $this->config = $config;
    }

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id' => 'page_id']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->via('page');
    }

    public function getConfigAsJson()
    {
        $result = [];
        foreach($this->config ?? [] as $key => $value) {
            if (!$this->canGetProperty($key)) {
                $result[$key] = $value;
            }
        }
        return Json::encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * @param HeramsResponse[]|iterable $data
     * @return \Generator
     */
    private function prepareData(iterable $data)
    {
        if ($this->transpose) {
            foreach($data as $key => $value) {
                yield from $value->getSubjects();
            }
        } else {
            yield from $data;
        }
    }
    public function getTitle(): ?string
    {
        return $this->config['title'] ?? null;
    }

    public function setTitle($value)
    {
        $config = $this->config;
        if (empty($value)) {
            unset($config['title']);
        } else {
            $config['title'] = $value;
        }
        $this->config = $config;
    }

    public function rules()
    {
        return [
            [['sort', 'type', 'transpose', 'code'], RequiredValidator::class],
            [['type'], RangeValidator::class, 'range' => array_keys($this->typeOptions())],
            [['sort'], NumberValidator::class],
            [['transpose'], BooleanValidator::class],
            'colors' => [['colors'], SafeValidator::class],
        ];
    }

    public function attributeLabels()
    {
        return [
            'colors.code' => 'Answer code'
        ];
    }





    public function typeOptions()
    {
        return [
            'map' => 'A dashboard element that shows a map, size 2x2',
            'chart' => 'A chart, size 1x1',
            'barchart' => 'A bar chart, size 1x1',
            'table' => 'A table, size 2x1'
        ];
    }

    public function attributeHints()
    {
        return [
            'sort' => \Yii::t('app', 'Determines the order of elements on a page, elements shown in ascending order'),
            'transpose' => \Yii::t('app', 'Whether this element needs data at facility (HF) or subject (service) level'),
        ];
    }


}