<?php

declare(strict_types=1);

namespace prime\models\ar\elements;

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

    /**
     * @param iterable<HeramsFacilityRecordInterface $data
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
            ->withType(ChartType::from($this->config['type']))
            ->withData($formatted['data'])
            ->withUpdateRoute([
                'element/update',
                'id' => $this->id,
            ])
            ->withN($formatted['n'])
            ->withTitle($this->config['title'] ?? '')
            ->finish();
    }

    public function toConfigurationArray(): array
    {
        return [
            ...($this->config ?? []),
            'sort' => $this->sort,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
