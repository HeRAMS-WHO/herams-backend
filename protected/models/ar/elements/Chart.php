<?php


namespace prime\models\ar\elements;

use prime\models\ar\Element;
use prime\widgets\chart\Chart as ChartWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class Chart extends Element
{
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'chartType' => \Yii::t('app', 'Chart type')
        ]);
    }

    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): Widget {
        return new ChartWidget($this, array_merge($this->getWidgetConfig(), [
            'data' => $data,
            'skipEmpty' => true,
            'survey' => $survey,
        ]));
    }

    public function setChartType(string $value): void
    {
        if (!isset($this->chartTypeOptions()[$value])) {
            throw new \InvalidArgumentException('Invalid chart type');
        }
        $config = $this->config;
        if ($value == ChartWidget::TYPE_DOUGHNUT) {
            unset($config['chartType']);
        } else {
            $config['chartType'] = $value;
        }
        $this->config = $config;
    }

    public function getChartType(): string
    {
        return $this->getWidgetConfig()['chartType'] ?? ChartWidget::TYPE_DOUGHNUT;
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['title'], StringValidator::class, 'min' => 1, 'max' => 100],
            [['chartType'], SafeValidator::class],
        ]);
    }


    public function chartTypeOptions(): array
    {
        return [
            ChartWidget::TYPE_DOUGHNUT => \Yii::t('app', 'Donut'),
            ChartWidget::TYPE_BAR => \Yii::t('app', 'Bar')

        ];
    }
}
