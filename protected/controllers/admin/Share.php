<?php

namespace prime\controllers\admin;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\GlobalPermission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
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
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        User $user
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $accessCheck->requireGlobalPermission(Permission::PERMISSION_ADMIN);
        $permissions = [
            Permission::PERMISSION_EXPORT,
            Permission::PERMISSION_MANAGE_WORKSPACES,
            Permission::PERMISSION_SURVEY_DATA,
            Permission::PERMISSION_READ,
            Permission::PERMISSION_SHARE,
            Permission::PERMISSION_CREATE_PROJECT,
            Permission::PERMISSION_DEBUG_TOOLBAR,
            Permission::PERMISSION_ADMIN
        ];
        $model = new ShareForm(
            new GlobalPermission(),
            $abacManager,
            $abacResolver,
            $user->identity,
            $mailer,
            $urlSigner,
            $permissions
        );

        $model->confirmationMessage = \Yii::t('app', 'Are you sure you want to revoke this global permission?');
        if ($request->isPost) {
            if ($model->load($request->bodyParams)) {
                $model->createRecords();
                $notificationService->success(\Yii::t(
                    'app',
                    'Global permissions granted to: <strong>{users}</strong> and invited users: <strong>{invitedUsers}</strong>',
                    [
                        'users' => implode(', ', array_map(function ($model) {
                            return $model->name;
                        }, $model->getUsers()->all())),
                        'invitedUsers' => implode(', ', $model->getInviteEmailAddresses()),
                    ]
                ));
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('share', [
            'model' => $model
        ]);
    }
}
