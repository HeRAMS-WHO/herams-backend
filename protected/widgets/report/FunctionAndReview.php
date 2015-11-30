<?php

namespace prime\widgets\report;

use yii\base\Widget;

class FunctionAndReview extends Widget
{
    public $number;
    public $title;
    public $score;
    public $scores = [];
    public $notes = [];

    public function run()
    {
        return $this->render('functionAndReview');
    }


}