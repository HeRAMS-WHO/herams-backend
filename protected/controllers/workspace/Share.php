<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\exceptions\NoGrantablePermissions;
use prime\models\ar\Permission;
use prime\models\forms\Share as ShareForm;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
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
        WorkspaceRepository $workspaceRepository,
        Request $request,
        AuthManager $abacManager,
        Resolver $abacResolver,
        User $user,
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspaceId = new WorkspaceId($id);

        $workspace = $workspaceRepository->retrieveForShare($workspaceId);

        try {
            $model = new ShareForm(
                $workspace,
                $abacManager,
                $abacResolver,
                $user->identity,
                $mailer,
                $urlSigner,
                [
                    Permission::PERMISSION_SURVEY_DATA,
                    Permission::PERMISSION_CREATE_FACILITY,
                    Permission::PERMISSION_EXPORT,
                    Permission::PERMISSION_SHARE,
                    Permission::PERMISSION_SUPER_SHARE,

                    Permission::ROLE_LEAD => \Yii::t('app', 'Workspace owner'),
                ]
            );
        } catch (NoGrantablePermissions $e) {
            $notificationService->error('There are no permissions that you can share for this workspace');
            return $this->controller->redirect($request->getReferrer());
        }

        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $model->createRecords();
            $notificationService->success(\Yii::t(
                'app',
                'Workspace <strong>{modelName}</strong> has been shared with: <strong>{users}</strong> and invited users: <strong>{invitedUsers}</strong>',
                [
                    'modelName' => $workspace->title,
                    'users' => implode(', ', array_map(function ($model) {
                        return $model->name;
                    }, $model->getUsers()->all())),
                    'invitedUsers' => implode(', ', $model->getInviteEmailAddresses()),
                ]
            ));
            return $this->controller->refresh();
        }

        return $this->controller->render('share', [
            'model' => $model,
            'workspace' => $workspace,
            'tabMenuModel' => $workspaceRepository->retrieveForTabMenu($workspaceId),
        ]);
    }
}
