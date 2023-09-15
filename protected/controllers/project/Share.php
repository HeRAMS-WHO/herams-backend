<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\components\NotificationService;
use prime\exceptions\NoGrantablePermissions;
use prime\models\ar\read\Project;
use prime\models\forms\Share as ShareForm;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;
use yii\web\User;
use function iter\toArray;

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
        BreadcrumbService $breadcrumbService,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne([
            'id' => $id,
        ]);
        $projectId = new ProjectId($id);
        $accessCheck->requirePermission($project, PermissionOld::PERMISSION_SHARE, \Yii::t('app', 'You are not allowed to share this project'));

        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );

        try {
            $model = new ShareForm(
                $project,
                $abacManager,
                $abacResolver,
                $user->identity,
                $mailer,
                $urlSigner,
                [
                    PermissionOld::PERMISSION_READ,
                    PermissionOld::PERMISSION_SURVEY_DATA,
                    PermissionOld::PERMISSION_CREATE_FACILITY,
                    PermissionOld::PERMISSION_EXPORT,
                    PermissionOld::PERMISSION_MANAGE_WORKSPACES,
                    PermissionOld::PERMISSION_SHARE,
                    PermissionOld::PERMISSION_MANAGE_DASHBOARD,
                    PermissionOld::PERMISSION_WRITE,
                    PermissionOld::PERMISSION_SUPER_SHARE,
                    PermissionOld::PERMISSION_SURVEY_BACKEND,

                    PermissionOld::ROLE_LEAD => \Yii::t('app', 'Project coordinator'),
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
            'project' => $project,
        ]);
    }
}
