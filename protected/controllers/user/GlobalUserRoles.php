<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\Controller;
use yii\base\Action;
use yii\web\Request;

class GlobalUserRoles extends Action
{
    public function run(Request $request, int $id)
    {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        return $this->controller->render('global-user-roles', [
            'userId' => $id,
        ]);
    }
}
