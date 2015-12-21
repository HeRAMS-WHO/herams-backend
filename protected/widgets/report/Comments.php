<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;

class Comments extends Widget
{
    /**
     * [ 'title' => ['comment1', 'comment2']]
     * @var array
     */
    public $comments;
    public $beginCount = 0;

    public function run()
    {
        $result = '';
        $count = $this->beginCount;
        foreach($this->comments as $title => $comments) {
            $result .= Html::beginTag('div', ['class' => 'row no-break']);

            $result .= Html::beginTag('div', ['class' => 'col-xs-12']);
            //$result .= Html::tag('h3', $count . '. ' . $title) . '<hr>';
            $result .= Html::tag('h4', \Yii::t('ccpm', 'Comments'), ['style' => ['height' => '40px']]);
            $result .= Html::endTag('div');

            $result .= Html::beginTag('div', ['class' => ['col-xs-12'], 'style' => ['margin-top' => '3px', 'margin-left' => '20px']]);
            $result .= Table::widget([
                'rows' => array_map(function($comment) {
                    return ['cells' => [$comment]];
                }, $comments),
                'options' => [
                    'class' => 'table-striped'
                ]
            ]);
            $result .= Html::endTag('div');

            $result .= Html::endTag('div');
            $count++;
        }
        return $result;
    }


}