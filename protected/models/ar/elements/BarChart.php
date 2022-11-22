<?php

namespace prime\models\ar\elements;

use herams\common\domain\element\Element;
use prime\widgets\chart\Chart as ChartWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;

class BarChart extends Element
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): Widget {
        return new ChartWidget($this, array_merge(
            [
                'type' => ChartWidget::TYPE_BAR,
                'skipEmpty' => true,
                'data' => $data,
                'survey' => $survey,
            ],
            $this->getWidgetConfig()
        ));
    }
}
