<?php

namespace prime\models\ar;

use herams\common\interfaces\HeramsResponseInterface;
use herams\common\interfaces\PageInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\Page;
use herams\common\models\Project;
use prime\interfaces\Exportable;
use prime\models\ar\elements\BarChart;
use prime\models\ar\elements\Chart;
use prime\models\ar\elements\Map;
use prime\models\ar\elements\Svelte;
use prime\models\ar\elements\Table;
use prime\queries\ElementQuery;
use prime\widgets\element\Element as ElementWidget;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\InvalidArgumentException;
use yii\base\NotSupportedException;
use yii\helpers\Json;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

/**
 * @property int $id
 * @property int $page_id
 * @property string $type
 * @property array $config
 * @property int $sort
 * @property boolean $transpose
 * @property int $width
 * @property int $height
 * @property string $code
 *
 * @property-read Page $page
 * @property-read Project $project
 */
class Element extends ActiveRecord implements Exportable
{
    public const TYPE_BARCHART = 'barchart';

    public const TYPE_CHART = 'chart';

    public const TYPE_MAP = 'map';

    public const TYPE_TABLE = 'table';

    final public static function find(): ElementQuery
    {
        return new ElementQuery(self::class);
    }

    public function __construct($config = [])
    {
        $this->transpose = 0;
        $this->width = 1;
        $this->height = 1;
        parent::__construct($config);
    }

    protected function getWidgetConfig(): array
    {
        return array_merge($this->config, [
            'width' => $this->width,
            'height' => $this->height,
        ]);
    }

    public static function tableName()
    {
        return '{{%element}}';
    }

    public static function instantiate($row): self
    {
        if (! isset($row['type'])) {
            throw new \InvalidArgumentException('Type must be set');
        }

        $element = match ($row['type']) {
            'map' => new Map(),
            'chart' => new Chart(),
            'barchart' => new BarChart(),
            'table' => new Table(),
            default => new Svelte()
        };
        $element->type = $row['type'];

        return $element;
    }

    protected function getWidgetInternal(SurveyInterface $survey, iterable $data)
    {
        throw new NotSupportedException('This must be implemented in a subclass');
    }

    final public function getWidget(SurveyInterface $survey, iterable $data, PageInterface $page): ElementWidget
    {
        return $this->getWidgetInternal($survey, $page->filterResponses($this->prepareData($data)));
    }

    final protected function findQuestionByCode(SurveyInterface $survey, string $text): ?QuestionInterface
    {
        foreach ($survey->getGroups() as $group) {
            foreach ($group->getQuestions() as $question) {
                if ($question->getTitle() === $text) {
                    return $question;
                }
            }
        }
        return null;
    }

    public function formName()
    {
        return 'Element';
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
        return $this->hasOne(Page::class, [
            'id' => 'page_id',
        ])->inverseOf('elements');
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, [
            'id' => 'project_id',
        ])->via('page');
    }

    public function getConfigAsJson()
    {
        $result = [];
        foreach ($this->config ?? [] as $key => $value) {
            if (! $this->canGetProperty($key)) {
                $result[$key] = $value;
            }
        }
        return Json::encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * @param HeramsResponseInterface[]|iterable $data
     * @return \Generator
     */
    private function prepareData(iterable $data)
    {
        if ($this->transpose) {
            foreach ($data as $key => $value) {
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

    public function rules(): array
    {
        return [
            [['sort', 'type', 'transpose', 'code', 'width', 'height'], RequiredValidator::class],
            [['type'],
                RangeValidator::class,
                'range' => array_keys($this->typeOptions()),
            ],
            [['width', 'height'],
                NumberValidator::class,
                'integerOnly' => true,
                'min' => 1,
                'max' => 4,
            ],
            [['sort'],
                NumberValidator::class,
                'integerOnly' => true,
            ],
            [['transpose'], BooleanValidator::class],
            'colors' => [['colors'], SafeValidator::class],
        ];
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'colors.code' => 'Answer code',
            'type' => \Yii::t('app', 'Type'),
            'page_id' => \Yii::t('app', 'Page'),
            'config' => \Yii::t('app', 'Config'),
            'sort' => \Yii::t('app', 'Sort'),
            'transpose' => \Yii::t('app', 'Transpose'),
            'width' => \Yii::t('app', 'Width'),
            'height' => \Yii::t('app', 'Height'),
        ]);
    }

    public function typeOptions(): array
    {
        return [
            self::TYPE_MAP => \Yii::t('app', 'A dashboard element that shows a map'),
            self::TYPE_CHART => \Yii::t('app', 'A chart'),
            self::TYPE_BARCHART => \Yii::t('app', 'A bar chart'),
            self::TYPE_TABLE => \Yii::t('app', 'A table'),
        ];
    }

    public function attributeHints(): array
    {
        return [
            'sort' => \Yii::t('app', 'Determines the order of elements on a page, elements shown in ascending order'),
            'transpose' => \Yii::t('app', 'Whether this element needs data at facility (HF) or subject (service) level'),
        ];
    }

    public function export(): array
    {
        $attributes = $this->attributes;
        foreach ($this->primaryKey() as $key) {
            unset($attributes[$key]);
        }
        unset($attributes['page_id']);
        return [
            'attributes' => $attributes,
            'type' => 'element',
        ];
    }

    /**
     * @param Page $parent
     */
    public static function import($parent, array $data): Element
    {
        if (! $parent instanceof Page) {
            throw new \InvalidArgumentException('Parent must be instance of page');
        }
        $result = Element::instantiate($data['attributes']);
        $result->setAttributes($data['attributes'], false);
        $result->page_id = $parent->id;
        if (! $result->validate()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($result->errors, true));
        }
        $result->save(false);
        return $result;
    }
}
