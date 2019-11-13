<?php
declare(strict_types=1);

namespace prime\controllers\user;


use prime\models\ar\User;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Index extends Action
{

    public function run()
    {
        $this->controller->layout = 'admin';

        return $this->controller->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => User::find(),

            ]),
            'searchModel' => new User()
        ]);
    }

}