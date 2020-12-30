<?php
declare(strict_types=1);

namespace prime\controllers\user;

use yii\base\Action;
use yii\web\Request;

class Index extends Action
{

    public function run(Request $request)
    {
        $this->controller->layout = 'admin-screen';
        $search = new \prime\models\search\User();



        return $this->controller->render('index', [
            'dataProvider' => $search->search($request->queryParams),
            'searchModel' => $search
        ]);
    }
}
