<?php


namespace prime\controllers\site;


use yii\base\Action;

class Admin extends Action
{
    public function run()
    {
        $this->controller->layout = 'css3-grid';
        return $this->controller->render('admin');
    }
}