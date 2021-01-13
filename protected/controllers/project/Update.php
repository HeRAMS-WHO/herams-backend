<?php


namespace prime\controllers\project;

use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
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
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = \prime\components\Controller::LAYOUT_ADMIN_TABS;
        $model = Project::findOne(['id' => $id]);

        $accessCheck->requirePermission($model, Permission::PERMISSION_WRITE);

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
