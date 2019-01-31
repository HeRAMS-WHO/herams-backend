<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use prime\widgets\table\Table as TableWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;

class Table extends Element
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): Widget
    {
        return new TableWidget(array_merge([
            'data' => $data,
            'survey' => $survey,
        ], $this->config));

    }
}