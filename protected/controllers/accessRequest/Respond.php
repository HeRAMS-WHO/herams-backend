<?php
declare(strict_types=1);

namespace prime\controllers\accessRequest;

use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\forms\accessRequest\Respond as RespondFormModel;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\Request;
use yii\web\User as UserComponent;

class Respond extends Action
{
    public function run(
        Request $request,
        UserComponent $user,
        AuthManager $abacManager,
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        int $id
    ) {
        $accessRequest = AccessRequest::findOne(['id' => $id]);

        $accessCheck->requirePermission($accessRequest, Permission::PERMISSION_RESPOND);
        $model = \Yii::createObject(RespondFormModel::class, [
            $accessRequest,
            $abacManager,
            $user->identity,
        ]);

        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $model->createRecords();
            if ($model->getAccessRequest()->accepted) {
                $notificationService->success(\Yii::t('app', 'Access request has been <strong>granted</strong>.'));
            } else {
                $notificationService->success(\Yii::t('app', 'Access request has been <strong>revoked</strong>.'));
            }
            return $this->controller->redirect(['access-request/index']);
        }

        return $this->controller->render(
            'respond',
            [
                'model' => $model,
            ]
        );
    }
}
