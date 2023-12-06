<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\Controller;
use prime\models\search\User;
use yii\base\Action;
use yii\web\Request;

class Material extends Action
{
    public function run(Request $request)
    {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $search = new User();

        return $this->controller->render('material', [
            'dataProvider' => $search->search($request->queryParams),
            'searchModel' => $search,
        ]);
    }
}
