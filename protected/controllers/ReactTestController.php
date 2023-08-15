<?php

namespace prime\controllers;

use prime\components\Controller;

class ReactTestController extends \prime\components\Controller
{
    public $layout = Controller::LAYOUT_BASE;

    public function actionIndex()
    {
        return $this->render('test-react');
    }
}
