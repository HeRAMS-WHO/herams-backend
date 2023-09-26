<?php

namespace prime\controllers\role;

use prime\components\Controller;
use yii\base\Action;

class Update extends Action
{
    public function run(int $id)
    {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        return $this->controller->render('update');
    }
}
