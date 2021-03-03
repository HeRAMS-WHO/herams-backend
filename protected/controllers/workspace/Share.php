<?php


namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\exceptions\NoGrantablePermissions;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\Share as ShareForm;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Share extends Action
{
    public function run(
        NotificationService $notificationService,
        Request $request,
        AuthManager $abacManager,
        Resolver $abacResolver,
        AccessCheckInterface $accessCheck,
        User $user,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        $accessCheck->requirePermission($workspace, Permission::PERMISSION_SHARE);

        try {
            $model = new ShareForm($workspace, $abacManager, $abacResolver, $user->identity, [
                Permission::PERMISSION_SURVEY_DATA,
                Permission::PERMISSION_CREATE_FACILITY,
                Permission::PERMISSION_EXPORT,
                Permission::PERMISSION_SHARE,
                Permission::PERMISSION_SUPER_SHARE,

                Permission::ROLE_LEAD,
            ]);
        } catch (NoGrantablePermissions $e) {
            $notificationService->error('There are no permissions that you can share for this workspace');
            return $this->controller->redirect($request->getReferrer());
        }

        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $model->createRecords();
            $notificationService->success(\Yii::t(
                'app',
                "Workspace <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                [
                    'modelName' => $workspace->title,
                    'users' => implode(', ', array_map(function ($model) {
                        return $model->name;
                    }, $model->getUsers()->all()))
                ]
            ));
            return $this->controller->refresh();
        }

        return $this->controller->render('share', [
            'model' => $model,
            'workspace' => $workspace
        ]);
    }
}
