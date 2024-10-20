<?php

declare(strict_types=1);

namespace prime\models\forms\element;

use Collecthor\DataInterfaces\ValueOptionInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use herams\api\validators\EnumValidator;
use herams\common\interfaces\HeramsVariableSetRepositoryInterface;
use herams\common\models\Page;
use herams\common\values\PageId;
use prime\components\View;
use prime\helpers\ChartHelper;
use prime\helpers\DeferredVariableSet;
use prime\helpers\HeramsVariableSet;
use prime\interfaces\DashboardWidgetInterface;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\objects\enums\ChartType;
use prime\objects\enums\DataSort;
use prime\validators\VariableValidator;
use prime\widgets\DashboardCard;
use SamIT\Yii2\abac\PermissionValidator;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

class Chart extends Model implements DashboardWidgetInterface
{
    private const DEFAULT_GROUP = 'default';

    public mixed $pageId = null;

    public mixed $width = 1;

    public mixed $height = 1;

    public mixed  $sort = 1;

    public mixed $title = "Chart";

    /**
     * @var list<string>
     */
    public mixed $variables = [];

    public mixed $groupingVariable = null;

    public mixed $dataSort = DataSort::Source;

    /**
     * @var array<string, string>
     */
    public array $colorMap = [];

    public ChartType $type = ChartType::Bar;

    public function __construct(
        private HeramsVariableSetRepositoryInterface $variableSetRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    private function getVariableSet(): VariableSetInterface
    {
        return $this->variableSetRepository->retrieveForPage(new PageId($this->pageId));
    }

    public function rules(): array
    {
        return [
            [['colorMap'], SafeValidator::class],
            [['width', 'height'],
                NumberValidator::class,
                'max' => 4,
                'min' => 1,
                'integerOnly' => true,
            ],
            [['sort'],
                NumberValidator::class,
                'min' => 1,
                'integerOnly' => true,
            ],
            [['title', 'variables'], RequiredValidator::class],
            PermissionValidator::create(['pageId'], Page::find()),

            [['dataSort'],
                EnumValidator::class,
                'enumClass' => DataSort::class,
            ],
            [['type'],
                EnumValidator::class,
                'enumClass' => ChartType::class,
            ],
            VariableValidator::multipleFromSet(new DeferredVariableSet(fn () => $this->getVariableSet()), 'variables')
                ->withCondition(fn ($model, $attribute) => ! $this->hasErrors('pageId')),
            VariableValidator::singleFromSet(new DeferredVariableSet(fn () => $this->getVariableSet()), 'groupingVariable'),

        ];
    }

    private function getGroup(HeramsFacilityRecordInterface $record, VariableSetInterface $variableSet): string
    {
        if (! isset($this->groupingVariable)) {
            return self::DEFAULT_GROUP;
        }

        $closedVariable = $variableSet->getVariable($this->groupingVariable);
        $value = $closedVariable->getValue($record);
        if ($value instanceof ValueOptionInterface) {
            return $value->getDisplayValue();
        }
        return $value->getRawValue();
    }

    /**
     * @param iterable<HeramsFacilityRecordInterface $data
     */
    public function renderWidget(HeramsVariableSet $variableSet, View $view, iterable $data): void
    {
        $locale = \Yii::$app->language;

        $chartHelper = new ChartHelper();
        $config = $chartHelper->createDataArray($variableSet, $this->groupingVariable, $this->variables, $this->colorMap, $locale, $data);

        DashboardCard::begin()
            ->withType($this->type)
            ->withData($config['data'])
            ->withN($config['n'])
            ->withTitle($this->title)
            ->finish();
    }

    public function toConfigurationArray(): array
    {
        return $this->attributes;
    }
}
