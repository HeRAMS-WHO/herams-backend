<?php


namespace prime\controllers\admin;


use yii\base\Action;

class Limesurvey extends Action
{

    public function run()
    {
        return $this->controller->render('limesurvey');
    }
}