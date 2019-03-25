<?php


namespace prime\controllers\admin;


use yii\base\Action;

class Dashboard extends Action
{

    public function run()
    {
        return $this->controller->render('dashboard');
    }
}