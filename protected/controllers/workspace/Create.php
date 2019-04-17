<?php


namespace prime\controllers\workspace;


use prime\components\NotificationService;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\forms\workspace\CreateUpdate;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{

    public function run(
        User $user,
        Request $request,
        NotificationService $notificationService,
        int $project_id
    ) {
        $project = Project::loadOne($project_id, [], Permission::PERMISSION_WRITE);

        $model = new Workspace();
        $model->tool_id = $project->id;

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save()) {
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