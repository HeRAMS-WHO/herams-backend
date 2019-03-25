<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use prime\widgets\map\DashboardMap as MapWidget;

class Map extends Element
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): Widget
    {
        return new MapWidget(array_merge([
            'data' => $data,
            'survey' => $survey,
        ], $this->config
        ));

    }
}