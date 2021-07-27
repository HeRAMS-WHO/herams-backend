<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\Request;

class Update extends Action
{
    public function run(
        Request $request,
        ProjectRepository $projectRepository,
        NotificationService $notificationService,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $projectId = new ProjectId($id);
        $model = $projectRepository->retrieveForUpdate($projectId);

        $hydrator = new ModelHydrator();
        if ($request->isPut) {
            $hydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate()) {
                $projectRepository->save($model);
                $notificationService->success(\Yii::t('app', "Project updated"));
                return $this->controller->refresh();
            }
        }

        $project = $projectRepository->retrieveForRead($projectId);
        return $this->controller->render('update', [
            'model' => $model,
            'project' => $project,
        ]);
    }
}
