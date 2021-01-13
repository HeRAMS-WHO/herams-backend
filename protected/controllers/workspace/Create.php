<?php


namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use yii\base\Action;
use yii\web\Request;

class Create extends Action
{

    public function run(
        AccessCheckInterface $accessCheck,
        Request $request,
        NotificationService $notificationService,
        int $project_id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne(['id' => $project_id]);
        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_WORKSPACES);

        $model = new Workspace();
        $model->tool_id = $project->id;

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(
                    \Yii::t('app', "Workspace <strong>{modelName}</strong> created", ['modelName' => $model->title])
                );
                return $this->controller->redirect(['project/workspaces', 'id' => $model->project->id]);
            }
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }
}
