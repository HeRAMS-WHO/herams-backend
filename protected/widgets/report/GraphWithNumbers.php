<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class GraphWithNumbers extends Widget
{
    public $total;
    public $part;
    public $title;
    public $texts;
    public $graphWidth = 6;

    public function init()
    {
        parent::init();
        $this->texts = ArrayHelper::merge([
            'top' => \Yii::t('report', 'Total'),
            'left' => \Yii::t('report', 'Total number of partners'),
            'right' => \Yii::t('report', 'Number partners responding')
        ], ArrayHelper::getValue($this, 'texts', []));
    }


    public function run()
    {
        $result = Html::beginTag('div', ['class' => 'row']);
        $result .= isset($this->title) ? Html::tag('div', $this->title, ['class' => 'col-xs-12']) : '';
        $result .= Html::beginTag('div', ['class' => 'col-xs-12']);
        $result .= Columns::widget(
            [
                'items' => [
                    [
                        'content' => 'graph',
                        'width' => $this->graphWidth
                    ],
                    [
                        'columns' => [
                            'items' => [
                                [
                                    'content' => Block::widget(['items' => [$this->texts['top'], ['content' => round(($this->part / $this->total) * 100) . ' %', 'htmlOptions' => ['class' => 'text-medium']]]]),
                                    'width' => 2
                                ],
                                Block::widget(['items' => [$this->texts['left'], ['content' => $this->total, 'htmlOptions' => ['class' => '']]]]),
                                Block::widget(['items' => [$this->texts['right'], ['content' => $this->part, 'htmlOptions' => ['class' => '']]]])

                            ],
                            'columnsInRow' => 2
                        ],
                        'width' => 6
                    ]
                ],
                'columnsInRow' => $this->graphWidth + 6
            ]
        );
        $result .= Html::endTag('div');
        $result .= Html::endTag('div');
        return $result;
    }


}