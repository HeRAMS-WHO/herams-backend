<?php


namespace prime\controllers\workspace;


use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class Limesurvey extends Action
{

    public function run(
        User $user,
        int $id)
    {
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_LIMESURVEY, $workspace)) {
            throw new ForbiddenHttpException();
        }

        return $this->controller->render('limesurvey', [
            'model' => $workspace
        ]);
    }
}