<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use prime\widgets\map\Map as MapWidget;

class Map extends Element
{
    public function getWidget(
        SurveyInterface $survey,
        array $data
    ): Widget
    {
        return new MapWidget([
            'data' => $data,
            'survey' => $survey,
            'code' => $this->config['code'] ?? 'HF2'
        ]);

    }
}