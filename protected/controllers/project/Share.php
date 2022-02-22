<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\exceptions\NoGrantablePermissions;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\models\forms\Share as ShareForm;
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
        Request $request,
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        AuthManager $abacManager,
        Resolver $abacResolver,
        User $user,
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne(['id' => $id]);

        $accessCheck->requirePermission($project, Permission::PERMISSION_SHARE, \Yii::t('app', 'You are not allowed to share this project'));

        try {
            $model = new ShareForm(
                $project,
                $abacManager,
                $abacResolver,
                $user->identity,
                $mailer,
                $urlSigner,
                [
                    Permission::PERMISSION_READ,
                    Permission::PERMISSION_SURVEY_DATA,
                    Permission::PERMISSION_CREATE_FACILITY,
                    Permission::PERMISSION_EXPORT,
                    Permission::PERMISSION_MANAGE_WORKSPACES,
                    Permission::PERMISSION_SHARE,
                    Permission::PERMISSION_MANAGE_DASHBOARD,
                    Permission::PERMISSION_WRITE,
                    Permission::PERMISSION_SUPER_SHARE,
                    Permission::PERMISSION_SURVEY_BACKEND,

                    Permission::ROLE_LEAD => \Yii::t('app', 'Project coordinator'),
                ]
            );
        } catch (NoGrantablePermissions $e) {
            $notificationService->error('There are no permissions that you can share for this project');
            return $this->controller->redirect($request->getReferrer());
        }
        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->validate()) {
                $model->createRecords();
                $notificationService->success(\Yii::t(
                    'app',
                    'Project <strong>{modelName}</strong> has been shared with: <strong>{users}</strong> and invited users: <strong>{invitedUsers}</strong>',
                    [
                        'modelName' => $project->title,
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
            'model' => $model,
            'project' => $project
        ]);
    }
}
