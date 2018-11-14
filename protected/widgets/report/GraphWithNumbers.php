<?php

namespace prime\widgets\report;

use yii\base\Widget;

class GraphWithNumbers extends Widget
{
    public $total;
    public $part;
    public $title;
    public $texts = [];
    public $graphWidth = 6;

    public function init()
    {
        parent::init();
        $this->texts = array_merge([
            'top' => \Yii::t('report', 'Total'),
            'left' => \Yii::t('report', 'Total number of partners'),
            'right' => \Yii::t('report', 'Number partners responding')
        ], $this->texts);
    }


    public function run()
    {
        return $this->render('graphWithNumbers');
    }


}