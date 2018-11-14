<?php

namespace prime\widgets\report;

use app\components\Html;

class DeliverableBlock extends Block
{
    public $title;
    public $available;
    public $link;

    public function init()
    {
        parent::init();
        $this->items = [
            $this->title,
            Columns::widget([
                'items' => [
                    [
                        'content' => \Yii::t('app', 'Available') . ':<div class="spacer-small"></div>' . (isset($this->link) && !empty($this->link) ? \Yii::t('app', 'Link') . ':' : ' '),
                        'width' => 2
                    ],
                    [
                        'content' => $this->available . '<div class="spacer-small"></div>' . ((isset($this->link)) ? (strpos($this->link, 'http') !== false ? Html::a($this->link, $this->link) : $this->link) : ''),
                        'width' => 4
                    ]
                ]
            ])
        ];
    }
}