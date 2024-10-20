<?php

namespace prime\models\ar\elements;

use herams\common\domain\element\Element;
use prime\widgets\element\Element as ElementWidget;
use prime\widgets\table\Table as TableWidget;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class Table extends Element
{
    protected function getWidgetInternal(
        SurveyInterface $survey,
        iterable $data
    ): ElementWidget {
        return new TableWidget($this, array_merge([
            'data' => $data,
            'survey' => $survey,
        ], $this->getWidgetConfig()));
    }

    public function rules(): array
    {
        $result = array_merge(parent::rules(), [
            [['reasonCode'], RequiredValidator::class],
            [['groupCode'], RequiredValidator::class],
            [['title'],
                StringValidator::class,
                'min' => 1,
                'max' => 100,
            ],
        ]);
        unset($result['colors']);
        return $result;
    }

    public function getReasonCode(): ?string
    {
        return $this->getWidgetConfig()['reasonCode'] ?? null;
    }

    public function setReasonCode(string $value)
    {
        $config = $this->config;
        if (empty($value)) {
            unset($config['reasonCode']);
        } else {
            $config['reasonCode'] = $value;
        }
        $this->config = $config;
    }

    public function getGroupCode(): ?string
    {
        return $this->getWidgetConfig()['groupCode'] ?? null;
    }

    public function setGroupCode(string $value)
    {
        $config = $this->config;
        if (empty($value)) {
            unset($config['groupCode']);
        } else {
            $config['groupCode'] = $value;
        }
        $this->config = $config;
    }
}
