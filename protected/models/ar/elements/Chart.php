<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use prime\widgets\chart\Chart as ChartWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;

class Chart extends Element
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): Widget
    {
        return new ChartWidget(array_merge($this->config, [
            'data' => $data,
            'skipEmpty' => true,
            'survey' => $survey,
            'type' => ChartWidget::TYPE_DOUGHNUT
        ]));

    }


}