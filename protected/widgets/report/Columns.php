<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Columns extends Widget
{
    public $items = [];
    public $columnsInRow = 6;
    public $rowOptions = [];

    public function init()
    {
        $this->rowOptions = ArrayHelper::merge(['class' => ['row'], 'id' => $this->getId()], $this->rowOptions);
    }


    public function run()
    {
        $result = Html::beginTag('div', $this->rowOptions);
        foreach($this->items as $column) {
            if(is_string($column)) {
                $column = ['content' => $column];
            }

            $column = ArrayHelper::merge([
                'width' => 1
            ], $column);

            if(isset($column['columns'])) {
                $column['content'] =
                    self::widget($column['columns'])
                ;
            }

            $width = floor(($column['width'] * (100 / $this->columnsInRow)) * 100) / 100;
            $options = ArrayHelper::merge(['style' => ['width' => $width . '%'], 'class' => 'col-xs-12'], ArrayHelper::getValue($column, 'options', []));
            $result .= Html::tag('div', $column['content'], $options);
        }
        $result .= Html::endTag('div');
        return $result;
    }
}