<?php
declare(strict_types=1);

namespace prime\controllers\session;


use prime\models\forms\LoginForm;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{

    public function run(User $user, Request $request)
    {
        if (!$user->getIsGuest()) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();

        if ($model->load($request->getBodyParams())
            && $model->login()
        ) {
            return $this->controller->goBack();
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }
}