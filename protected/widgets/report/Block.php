<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Block extends Widget
{
    public $items = [];
    public $htmlOptions = [];

    public function init()
    {
        parent::init();
        $this->htmlOptions = ArrayHelper::merge([
            'style' => 'width: 100%; margin-bottom: 5px;'
        ], $this->htmlOptions);
    }

    public function run()
    {
        $result = '';
        $result .= Html::beginTag('div', $this->htmlOptions);
        foreach($this->items as $key => $item) {
            if ($key != 0) {
                $result .= '<hr/>';
            }

            if(is_string($item)) {
                $item = ['content' => $item];
            }
            $result .= Html::tag('div', $item['content'], isset($item['htmlOptions']) ? $item['htmlOptions'] : []);
        }
        $result .= Html::endTag('div');
        return $result;
    }
}