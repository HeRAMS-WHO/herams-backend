<?php


namespace prime\controllers\workspace;


use prime\components\NotificationService;
use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Request;

class Configure extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        int $id
    ) {
        /** @var Workspace $model */
        $model = Workspace::loadOne($id, [], Permission::PERMISSION_WRITE);
        // Form model.
        $token = new Token($model->getToken());

        if ($request->isPut && $token->load($request->bodyParams) && $token->save(true)) {
            $notificationService->success(\Yii::t('app', "Token updated."));
            $this->controller->refresh();
        }
        return $this->controller->render('configure', [
            'token' => $token,
            'model' => $model
        ]);
    }

}