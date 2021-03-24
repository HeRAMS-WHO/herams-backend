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

class Update extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $model = Project::findOne(['id' => $id]);

        $accessCheck->requirePermission($model, Permission::PERMISSION_WRITE);

        if ($request->isPut && $model->load($request->bodyParams) && $model->validate()) {
            $model->save(false);
            $notificationService->success(\Yii::t('app', "Project updated"));
            return $this->controller->refresh();
        }

        return $this->controller->render('update', [
            'project' => $model,
        ]);
    }
}
