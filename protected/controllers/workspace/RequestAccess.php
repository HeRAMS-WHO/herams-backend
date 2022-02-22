<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\accessRequest\Create as RequestAccessForm;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\Request;
use yii\web\User as UserComponent;

/**
 * Class RequestAccess
 * @package prime\controllers\workspace
 */
class RequestAccess extends Action
{
    public function run(
        AuthManager $abacManager,
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        Request $request,
        UserComponent $user,
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspace = $workspaceRepository->retrieveForRequestAccess(new WorkspaceId($id));


        $accessCheck->requirePermission(
            $workspace->project,
            Permission::PERMISSION_SUMMARY,
            \Yii::t('app', 'You are not allowed to request access to this workspace')
        );
        $model = new RequestAccessForm(
            $workspace,
            [
                AccessRequest::PERMISSION_CONTRIBUTE => \Yii::t('app', 'Contribute data'),
                AccessRequest::PERMISSION_EXPORT => \Yii::t('app', 'Download data'),
                AccessRequest::PERMISSION_OTHER => \Yii::t('app', 'Other'),
            ],
            $abacManager,
            $user->identity
        );
        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $model->createRecords();
            $notificationService->success(\Yii::t(
                'app',
                'Requested access to {modelName}',
                [
                    'modelName' => $workspace->title,
                ]
            ));
            return $this->controller->goBack();
        }


        return $this->controller->render('request-access', [
            'model' => $model,
            'workspace' => $workspace
        ]);
    }
}
