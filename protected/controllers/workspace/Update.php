<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;

class Update extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        NotificationService $notificationService,
        Request $request,
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspaceId = new WorkspaceId($id);
        $model = $workspaceRepository->retrieveForUpdate($workspaceId);

        if ($request->isPut) {
            $modelHydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate()) {
                $workspaceRepository->save($model);
                $notificationService->success(\Yii::t('app', 'Workspace updated'));

                return $this->controller->redirect(['workspace/responses', 'id' => $workspaceId]);
            }
        }

        return $this->controller->render('update', [
            'model' => $model,
            'tabMenuModel' => $workspaceRepository->retrieveForTabMenu($workspaceId)
        ]);
    }
}
