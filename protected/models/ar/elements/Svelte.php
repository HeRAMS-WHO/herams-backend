<?php
declare(strict_types=1);

namespace prime\models\ar\elements;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\ValueOptionInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\assets\DashboardCardsBundle;
use prime\components\View;
use prime\helpers\HeramsVariableSet;
use prime\interfaces\DashboardWidgetInterface;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\models\ar\Element;
use prime\widgets\element\Element as ElementWidget;
use prime\widgets\map\DashboardMap as MapWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\InvalidArgumentException;
use yii\base\Widget;
use yii\helpers\Html;
use function iter\chain;
use function iter\map;
use function iter\toArray;

class Svelte extends Element implements DashboardWidgetInterface
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): ElementWidget {
        return new MapWidget($this, array_merge([
            'data' => $data,
            'survey' => $survey,
        ], $this->getWidgetConfig()));
    }

    private const DEFAULT_GROUP = 'default';
    private function getGroup(HeramsFacilityRecordInterface $record, VariableSetInterface $variableSet): string
    {
        if (!isset($this->config['groupingVariable'])) {
            return self::DEFAULT_GROUP;
        }

        $closedVariable = $variableSet->getVariable($this->config['groupingVariable']);
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
        foreach($this->config['variables'] as $variableName) {
            $variables[] = $variableSet->getVariable($variableName);
        }

        // Get all categories for the chart.
        if (isset($this->config['groupingVariable'])) {
            $groupingVariable = $variableSet->getVariable($this->config['groupingVariable']);
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
                            'color' => $this->config['colorMap'][$key] ?? 'rgb(' . implode(',',
                                    [mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)]) . ')',
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
            'type' => $this->type,
            'data' => toArray(chain(...array_values($points ?? []))),
            'title' => $this->config['title'] ?? ''
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
            'class' => ['card-widget'],
            'id' => $widget->getId(),
        ]);


//        echo(json_encode($config, JSON_PRETTY_PRINT));

        echo Html::endTag('div');
    }
}
