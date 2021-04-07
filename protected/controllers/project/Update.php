<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\behaviors\LocalizableWriteBehavior;
use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
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

        $project = $projectRepository->retrieveForRead($projectId);

        $hydrator = new ModelHydrator();
        if ($request->isPut) {
            $hydrator->hydrateFromRequest($model, $request);
            if ($model->validate()) {
                $projectRepository->save($model);
                $notificationService->success(\Yii::t('app', "Project updated"));
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('update', [
            'model' => $model,
            'project' => $project,
        ]);
    }
}
