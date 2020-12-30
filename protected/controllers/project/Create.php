<?php


namespace prime\controllers\project;

use prime\components\NotificationService;
use prime\models\ar\Project;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{
    public function run(
        User $user,
        NotificationService $notificationService,
        Request $request
    ) {
        $this->controller->layout = 'admin-screen';
        $model = new Project();

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Project <strong>{project}</strong> created", [
                    'project' => $model->title
                ]));
                return $this->controller->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }
}
