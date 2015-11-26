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

    public function run()
    {
        $result = '';
        $count = 0;
        foreach($this->comments as $title => $comments) {
            $result .= Html::beginTag('div', ['class' => 'row']);

            $result .= Html::beginTag('div', ['class' => 'col-xs-12']);
            $result .= Html::tag('h3', $count . '. ' . $title) . '<hr>';
            $result .= Html::endTag('div');

            $result .= Html::beginTag('div', ['class' => ['col-xs-offset-1', 'col-xs-11']]);
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