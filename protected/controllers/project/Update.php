<?php


namespace prime\controllers\project;

use prime\components\NotificationService;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $id
    ) {
        $this->controller->layout = 'admin-screen';
        $model = Project::findOne(['id' => $id]);
        if (!isset($model)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_WRITE, $model)) {
            throw new ForbiddenHttpException('You do not have write permission');
        }

        $model->validate();
        if ($request->isPut) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Project updated"));
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('update', [
            'project' => $model
        ]);
    }
}
