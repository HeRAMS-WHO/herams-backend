<?php


namespace prime\controllers\workspace;

use prime\components\NotificationService;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Configure extends Action
{
    public function run(
        Request $request,
        User $user,
        NotificationService $notificationService,
        int $id
    ) {
        $this->controller->layout = 'admin-content';
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_ADMIN, $workspace)) {
            throw new ForbiddenHttpException();
        }
        // Form model.
        $token = new Token($workspace->getToken());

        if ($request->isPut && $token->load($request->bodyParams) && $token->save(true)) {
            $notificationService->success(\Yii::t('app', "Token updated"));
            $this->controller->refresh();
        }
        return $this->controller->render('configure', [
            'token' => $token,
            'model' => $workspace
        ]);
    }
}
