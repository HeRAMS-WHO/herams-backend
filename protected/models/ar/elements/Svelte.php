<?php
declare(strict_types=1);

namespace prime\models\ar\elements;

use Collecthor\DataInterfaces\ValueOptionInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\components\View;
use prime\helpers\ChartHelper;
use prime\helpers\HeramsVariableSet;
use prime\interfaces\DashboardWidgetInterface;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\models\ar\Element;
use prime\objects\enums\ChartType;
use prime\widgets\DashboardCard;
use prime\widgets\element\Element as ElementWidget;
use prime\widgets\map\DashboardMap as MapWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

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
        $locale = "nl";
        $formatted = (new ChartHelper())->createDataArray(
            $variableSet,
            $this->config['groupingVariable'] ?? null,
            $this->config['variables'],
            $this->config['colorMap'] ?? [],
            $locale,
            $data
        );
        DashboardCard::begin()
            ->withType(ChartType::from($this->type))
            ->withData($formatted['data'])
            ->withUpdateRoute(['element/update', 'id' => $this->id])
            ->withN($formatted['n'])
            ->withTitle($this->config['title'] ?? '')
            ->finish();



    }

    public function toConfigurationArray(): array
    {
        return [
            ...($this->config ?? []),
            'type' => $this->type,
            'sort' => $this->sort,
            'width' => $this->width,
            'height' => $this->height
        ];

    }
}
