<?php


namespace prime\controllers\project;


use prime\components\NotificationService;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $id
    )
    {
        $model = Project::loadOne($id);

        if (!$user->can(Permission::PERMISSION_WRITE, $model)) {
            throw new ForbiddenHttpException();
        }

        $model->validate();
        if ($request->isPut) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Project updated"));
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('update', [
            'model' => $model
        ]);
    }
}