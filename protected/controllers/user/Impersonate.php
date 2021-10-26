<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\models\ar\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

class Impersonate extends Action
{
    public function run(
        Request $request,
        User $user,
        int $id
    ): Response {
        if (!$request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        if (!$user->can(Permission::PERMISSION_ADMIN)) {
            throw new ForbiddenHttpException();
        }

        $targetUser = \prime\models\ar\User::findOne(['id' => $id]);
        if (!isset($targetUser)) {
            throw new NotFoundHttpException();
        }

        $user->login($targetUser);
        return $this->controller->goHome();
    }
}
