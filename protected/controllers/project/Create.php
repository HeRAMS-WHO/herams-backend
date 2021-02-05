<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        Request $request
    ) {
        $this->controller->layout = \prime\components\Controller::LAYOUT_ADMIN_TABS;

        $accessCheck->requireGlobalPermission(Permission::PERMISSION_CREATE_PROJECT);
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
