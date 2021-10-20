<?php

declare(strict_types=1);

namespace prime\controllers\session;

use yii\base\Action;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

class Delete extends Action
{

    public function run(Request $request, User $user): Response
    {
        if (!$request->isDelete) {
            throw new MethodNotAllowedHttpException();
        }
        $user->logout();
        return $this->controller->goHome();
    }
}
