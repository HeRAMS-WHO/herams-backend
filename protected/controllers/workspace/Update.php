<?php


namespace prime\controllers\workspace;


use prime\components\NotificationService;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\workspace\CreateUpdate;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        User $user,
        NotificationService $notificationService,
        $id
    ) {
        $this->controller->layout = 'form';
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_WRITE, $workspace)) {
            throw new ForbiddenHttpException();
        }

        if($request->isPut) {
            if($workspace->load($request->bodyParams) && $workspace->save()) {
                $notificationService->success(\Yii::t('app', "Workspace <strong>{modelName}</strong> has been updated.", [
                    'modelName' => $workspace->title
                ]));

                return $this->controller->redirect(['project/workspaces', 'id' => $workspace->project->id]);
            }
        }

        return $this->controller->render('update', [
            'model' => $workspace
        ]);
    }


}