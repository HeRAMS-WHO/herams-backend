<?php

declare(strict_types=1);

namespace prime\objects\enums;

enum ChartType: string {
    case Bar = "bar";
    case Donut = "donut";
    case EChartsPie = "echartspie";
    case EChartsBar = "echartsbar";

    private static function labels(): array {
        return [
            self::Bar->value => "Bar chart",
            self::Donut->value => "Donut chart",
            self::EChartsPie->value => "Pie chart",
            self::EChartsBar->value => "Bar chart (echarts)",
        ];
    }
    public function label(): string
    {
        return self::labels()[$this->value];
    }


    public static function options(): array
    {
        return self::labels();
    }
}
