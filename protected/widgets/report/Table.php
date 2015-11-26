<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Table extends Widget
{
    public $rows = [];
    public $rowOptions = [];
    public $columnOptions = [];
    public $cellOptions = [];
    public $options = [];

    public function run()
    {
        $result = Html::beginTag('table', $this->options);
        foreach($this->rows as $row) {
            if(!isset($row['cells'])) {
                throw new \Exception('Cells need to be set in a row');
            }

            $row['options'] = ArrayHelper::merge($this->rowOptions, ArrayHelper::getValue($row, 'options', []));
            $result .= Html::beginTag('tr', $row['options']);

            foreach($row['cells'] as $column => $cell) {
                if(is_string($cell)) {
                    $cell = ['content' => $cell];
                }

                if(!isset($cell['tag'])) {
                    $cell['tag'] = 'td';
                }

                $cellOptions = ArrayHelper::merge(ArrayHelper::getValue($this->columnOptions, $column, []), $this->cellOptions);
                $cell['options'] = ArrayHelper::merge($cellOptions, ArrayHelper::getValue($cell, 'options', []));
                $result .= Html::tag($cell['tag'], $cell['content'], $cell['options']);
            }

            $result .= Html::endTag('tr');
        }
        $result .= Html::endTag('table');
        return $result;
    }


}