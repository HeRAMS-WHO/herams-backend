<?php

namespace prime\controllers\role;

use prime\components\Controller;
use yii\base\Action;

class Index extends Action
{
    public function run()
    {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        return $this->controller->render('index');
    }
}
