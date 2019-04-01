<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\models\permissions\Permission;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class PermissionController extends Controller
{
    public function actionDelete(
        User $user,
        int $id,
        string $redirect
    ) {
        $permission = Permission::findOne(['id' => $id]);
        if (!isset($permission)) {
            throw new NotFoundHttpException();
        }
        $target = $permission->targetObject;
        if (!isset($target)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_SHARE, $target)) {
            throw new ForbiddenHttpException();
        }

        $permission->delete();

        return $this->redirect($redirect);
    }
}