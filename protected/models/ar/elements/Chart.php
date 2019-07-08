<?php


namespace prime\models\ar\elements;


use prime\models\ar\Element;
use prime\widgets\chart\Chart as ChartWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use yii\validators\StringValidator;

class Chart extends Element
{


    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): Widget
    {
        return new ChartWidget($this, array_merge($this->config, [
            'data' => $data,
            'skipEmpty' => true,
            'survey' => $survey,
            'type' => ChartWidget::TYPE_DOUGHNUT
        ]));


    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['title'], StringValidator::class, 'min' => 1, 'max' => 100]

        ]);
    }


}