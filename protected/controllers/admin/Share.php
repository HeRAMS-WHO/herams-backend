<?php


namespace prime\controllers\admin;


use prime\components\NotificationService;
use prime\models\ar\Permission;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\GlobalPermission;
use SamIT\abac\AuthManager;
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
        $this->controller->layout = 'form';
        if (!($user->can(Permission::PERMISSION_ADMIN))) {
            throw new ForbiddenHttpException();
        }
        $permissions = [
            Permission::PERMISSION_EXPORT,
            Permission::PERMISSION_MANAGE_WORKSPACES,
            Permission::PERMISSION_LIMESURVEY,
            Permission::PERMISSION_READ,
            Permission::PERMISSION_SHARE,
            Permission::PERMISSION_CREATE_PROJECT,
        ];
        $model = new ShareForm(new GlobalPermission(), $abacManager, $user->identity, $permissions);

        $model->confirmationMessage = \Yii::t('app', 'Are you sure you want to revoke this global permission?');
        if ($request->isPost) {
            if ($model->load($request->bodyParams)) {
                $model->createRecords();
                $notificationService->success(\Yii::t('app',
                    "Global permissions granted to {users}",
                    [
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