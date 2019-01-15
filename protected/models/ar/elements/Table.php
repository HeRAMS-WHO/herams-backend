<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use prime\widgets\table\Table as TableWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use prime\widgets\map\Map as MapWidget;

class Table extends Element
{
    public function getWidget(
        SurveyInterface $survey,
        array $data
    ): Widget
    {
        return new TableWidget(array_merge($this->config, [
            'data' => $data,
            'survey' => $survey,
        ]));

    }
}