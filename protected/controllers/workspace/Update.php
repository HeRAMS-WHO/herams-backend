<?php


namespace prime\controllers\workspace;


use prime\components\NotificationService;
use prime\models\forms\workspace\CreateUpdate;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        NotificationService $notificationService,
        $id
    ) {
        $model = CreateUpdate::loadOne($id, [], Permission::PERMISSION_ADMIN);
        $model->scenario = 'update';
        if($request->isPut) {
            if($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Workspace <strong>{modelName}</strong> has been updated.", [
                    'modelName' => $model->title
                ]));

                return $this->controller->redirect(['project/workspaces', 'id' => $model->project->id]);
            }
        }

        return $this->controller->render('update', [
            'model' => $model
        ]);
    }


}