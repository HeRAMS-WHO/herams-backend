<?php
declare(strict_types=1);

namespace prime\controllers\user;


use prime\models\ar\User;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\Request;

class Index extends Action
{

    public function run(Request $request)
    {
        $this->controller->layout = 'admin';
        $search = new \prime\models\search\User();



        return $this->controller->render('index', [
            'dataProvider' => $search->search($request->queryParams),
            'searchModel' => $search
        ]);
    }

}