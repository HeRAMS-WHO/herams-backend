<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use prime\widgets\element\Element as ElementWidget;
use prime\widgets\table\Table as TableWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

class Table extends Element
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): ElementWidget
    {
        return new TableWidget($this, array_merge([
            'data' => $data,
            'survey' => $survey,
        ], $this->config));

    }
}