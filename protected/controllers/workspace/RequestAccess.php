<?php
declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\accessRequest\Create as RequestAccessForm;
use yii\base\Action;
use yii\web\Request;

/**
 * Class RequestAccess
 * @package prime\controllers\workspace
 */
class RequestAccess extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        Request $request,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspace = Workspace::findOne(['id' => $id]);

        $accessCheck->requirePermission(
            $workspace->project,
            Permission::PERMISSION_SUMMARY,
            \Yii::t('app', 'You are not allowed to request access to this workspace')
        );

        /** @var RequestAccessForm $model */
        $model = new RequestAccessForm(
            $workspace,
            [
                AccessRequest::PERMISSION_READ => \Yii::t('app', 'View workspace'),
                AccessRequest::PERMISSION_EXPORT => \Yii::t('app', 'Download data'),
                AccessRequest::PERMISSION_WRITE => \Yii::t('app', 'Update workspace'),
            ]
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
