<?php


namespace prime\controllers\project;


use prime\components\NotificationService;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

class Check extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $id
    )
    {
        $model = Project::find()->with('workspaces')->one();

        if (!$user->can(Permission::PERMISSION_ADMIN, $model)) {
            throw new ForbiddenHttpException();
        }

        return $this->controller->render('check', ['project' => $model]);
    }
}