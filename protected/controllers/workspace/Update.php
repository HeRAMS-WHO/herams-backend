<?php


namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\workspace\CreateUpdate;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\helpers\Html;
use yii\i18n\I18N;
use yii\web\Request;

class Update extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        WorkspaceRepository $workspaceRepository,
        string $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspaceId = new WorkspaceId($id);
        $workspace = $workspaceRepository->retrieveForWrite($workspaceId);

        if ($request->isPut) {
            if ($workspace->load($request->bodyParams) && $workspace->save()) {
                $notificationService->success(\Yii::t('app', "Workspace {workspace} has been updated", [
                    'workspace' => Html::tag('strong', $workspace->title)
                ]));

                return $this->controller->redirect(['workspace/responses', 'id' => $workspace->id]);
            }
        }

        return $this->controller->render('update', [
            'model' => $workspace,
            'tabMenuModel' => $workspaceRepository->retrieveForTabMenu($workspaceId)
        ]);
    }
}
