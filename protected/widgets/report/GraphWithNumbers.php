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
        return $this->render('graphWithNumbers');
    }


}