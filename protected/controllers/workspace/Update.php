<?php


namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\workspace\CreateUpdate;
use yii\base\Action;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspace = Workspace::findOne(['id' => $id]);
        $accessCheck->requirePermission($workspace, Permission::PERMISSION_WRITE);

        if ($request->isPut) {
            if ($workspace->load($request->bodyParams) && $workspace->save()) {
                $notificationService->success(\Yii::t('app', "Workspace {workspace} has been updated", [
                    'workspace' => Html::tag('strong', $workspace->title)
                ]));

                return $this->controller->redirect(['workspace/view', 'id' => $workspace->id]);
            }
        }

        return $this->controller->render('update', [
            'model' => $workspace
        ]);
    }
}
