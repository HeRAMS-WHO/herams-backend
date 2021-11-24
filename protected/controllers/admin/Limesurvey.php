<?php

namespace prime\controllers\admin;

use prime\components\Controller;
use yii\base\Action;

class Limesurvey extends Action
{
    public function run()
    {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        return $this->controller->render('limesurvey');
    }
}
