<?php


namespace prime\controllers\admin;


use prime\components\NotificationService;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\GlobalPermission;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\values\Authorizable;
use SamIT\Yii2\abac\AccessChecker;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

class Share extends Action
{
    public function run(
        NotificationService $notificationService,
        Request $request,
        AuthManager $abacManager,
        User $user
    ) {
        if (!($user->can(Permission::PERMISSION_ADMIN))) {
            throw new ForbiddenHttpException();
        }
        $model = new ShareForm(new GlobalPermission(), $abacManager, $user->identity, [
            'permissionOptions' => [
                Permission::PERMISSION_ADMIN,
            ]
        ]);

        if ($request->isPost) {
            if ($model->load($request->bodyParams)) {
                $model->createRecords();
                $notificationService->success(\Yii::t('app',
                    "Workspace <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                    [
                        'modelName' => 'Global',
                        'users' => implode(', ', array_map(function ($model) {
                            return $model->name;
                        }, $model->getUsers()->all()))
                    ])

                );
                return $this->controller->refresh();
            }

        }


        return $this->controller->render('share', [
            'model' => $model,
        ]);
    }
}