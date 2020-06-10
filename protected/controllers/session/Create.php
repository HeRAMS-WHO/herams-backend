<?php
declare(strict_types=1);

namespace prime\controllers\session;


use prime\models\forms\LoginForm;
use prime\models\forms\user\RequestAccountForm;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{

    public function run(User $user, Request $request, CacheInterface $cache)
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
            'model' => $model,
            'requestAccountForm' => new RequestAccountForm($cache)
        ]);
    }
}