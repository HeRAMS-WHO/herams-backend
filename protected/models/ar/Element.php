<?php


namespace prime\models\ar;


use prime\components\JsonValidator;
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
use yii\base\Widget;
use yii\helpers\Json;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\SafeValidator;

/**
 *
 * @property boolean $transpose
 * @property array $config
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

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id' => 'page_id']);
    }

    public function getConfigAsJson()
    {
        return Json::encode($this->config, JSON_PRETTY_PRINT);
    }

    public function setConfigAsJson($value)
    {
        $this->config = Json::decode($value);
    }

    /**
     * @param HeramsResponse[]|iterable $data
     * @return \Generator
     */
    private function prepareData(iterable $data)
    {
        if ($this->transpose) {
            foreach($data as $key => $value) {
                foreach($value->getSubjects() as $subject) {
                    yield $subject;
                }
            }
        } else {
            foreach($data as $key => $value) {
                yield $value;
            }
        }
    }

    public function rules()
    {
        return [
            [['type'], RangeValidator::class, 'range' => array_keys($this->typeOptions())],
            [['sort'], NumberValidator::class],
            [['transpose'], BooleanValidator::class],
            [['configAsJson'], SafeValidator::class],
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