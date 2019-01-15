<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use prime\widgets\chart\Chart as ChartWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;

class Chart extends Element
{
    public function getWidget(
        SurveyInterface $survey,
        array $data
    ): Widget
    {
        return new ChartWidget([
            'data' => $data,
            'survey' => $survey,
            'code' => $this->config['code'] ?? 'HF2'
        ]);

    }


}