<?php

namespace prime\controllers;

use prime\components\Controller;

class ReactController extends \prime\components\Controller
{
    public $layout = Controller::LAYOUT_REACT;

    public function actionIndex()
    {
        return $this->render('index');
    }
}
