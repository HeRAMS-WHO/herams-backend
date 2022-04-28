<?php
declare(strict_types=1);

namespace prime\models\forms\element;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\ValueOptionInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\assets\DashboardCardsBundle;
use prime\components\View;
use prime\helpers\HeramsVariableSet;
use prime\interfaces\DashboardWidgetInterface;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\objects\enums\ChartType;
use prime\objects\enums\DataSort;
use prime\validators\EnumValidator;
use prime\values\PageId;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use function iter\chain;
use function iter\map;
use function iter\toArray;

class Chart extends Model implements DashboardWidgetInterface
{
    private const DEFAULT_GROUP = 'default';
    public null|PageId $pageId = null;
    public int $width = 1;
    public int $height = 1;
    public int $sort = 1;
    public string $title = "";

    /**
     * @var list<string>
     */
    public array $variables = [];
    public null|string $groupingVariable = null;

    public DataSort $dataSort = DataSort::Source;

    /**
     * @var array<string, string>
     */
    public array $colorMap = [];

    public ChartType $type = ChartType::Bar;

    public function rules(): array
    {
        return [
            [['variables', 'colorMap', 'type', 'groupingVariable'], SafeValidator::class],
            [['width', 'height', 'sort'], NumberValidator::class],
            [['title'], RequiredValidator::class],
            [['pageId'], PermissionValidator]
            [['dataSort'], EnumValidator::class, 'enumClass' => DataSort::class]




        ];
    }

    private function getGroup(HeramsFacilityRecordInterface $record, VariableSetInterface $variableSet): string
    {
        if (!isset($this->groupingVariable)) {
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
     * @param HeramsVariableSet $variableSet
     * @param View $view
     * @param iterable<HeramsFacilityRecordInterface $data
     * @return void
     */
    public function renderWidget(HeramsVariableSet $variableSet, View $view, iterable $data): void
    {
        $locale = null;
        $variables = [];
        foreach($this->variables as $variableName) {
            $variables[] = $variableSet->getVariable($variableName);
        }

        // Get all categories for the chart.
        if (isset($this->groupingVariable)) {
            $groupingVariable = $variableSet->getVariable($this->groupingVariable);
            if (!$groupingVariable instanceof ClosedVariableInterface) {
                throw new InvalidArgumentException('Grouping variable must be closed');
            }

            $groups = toArray(map(fn(ValueOptionInterface $option) => $option->getDisplayValue(), $groupingVariable->getValueOptions()));
        } else {
            $groups = [self::DEFAULT_GROUP];
        }

        $points = [];
        foreach($groups as $group) {
            foreach ($variables as $variable) {
                if ($variable instanceof ClosedVariableInterface) {
                    foreach ($variable->getValueOptions() as $valueOption) {
                        $key = $valueOption->getRawValue();
                        $points[$group][$key] = [
                            'key' => $key,
                            'group' => $group,
                            'label' => $valueOption->getDisplayValue($locale),
                            'value' => rand(1, 1000),
                            'color' => $this->colorMap[$key] ?? 'rgb(' + implode(',',
                                    [mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)]) + ')',
                        ];
                    }
                }
            }
        }
        // Iterate over data.
        foreach($data as $record) {
            $group = $this->getGroup($record, $variableSet);
            foreach ($variables as $variable) {
                $value = $variable->getValue($record)->getRawValue();
                if (isset($points[$group][$value])) {
                    $points[$group][$value]['value']++;
                }
            }
        }

        $widget = Widget::begin();
        $bundle = DashboardCardsBundle::register($view);
        $config = [
            'type' => $this->type->value,
            'data' => toArray(chain(...array_values($points ?? []))),
            'title' => $this->title
        ];

        $jsonConfig = json_encode($config);
        $js = <<<JS
          {$bundle->getImport("DashboardCard")}

          const app = new DashboardCard({
            target: document.getElementById("{$widget->getId()}"),
            props: $jsonConfig,
               
            
          })
        
        JS;

        $view->registerJs($js, View::POS_MODULE);
        echo Html::beginTag('div', [
            'id' => $widget->getId(),
        ]);


//        echo(json_encode($config, JSON_PRETTY_PRINT));

        echo Html::endTag('div');
    }
}
