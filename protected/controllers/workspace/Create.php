<?php
declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\workspace\CreateForLimesurvey;
use prime\objects\enums\ProjectType;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        ModelHydrator $modelHydrator,
        NotificationService $notificationService,
        Request $request,
        WorkspaceRepository $workspaceRepository,
        int $project_id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne(['id' => $project_id]);

        if (!$project) {
            throw new NotFoundHttpException('Project not found.');
        }
        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_WORKSPACES);

        if ($project->getType()->equals(ProjectType::limesurvey())) {
            $model = new CreateForLimesurvey(['tool_id' => new ProjectId($project->id)]);
        } else {
            $model = new \prime\models\forms\workspace\Create(['tool_id' => new ProjectId($project->id)]);
        }

        if ($request->isPost) {
            $modelHydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate()) {
                $workspaceRepository->create($model);
                $notificationService->success(
                    \Yii::t('app', "Workspace <strong>{modelName}</strong> created", ['modelName' => $model->title])
                );
                return $this->controller->redirect(['project/workspaces', 'id' => $project->id]);
            }
        }

        return $this->controller->render('create', [
            'model' => $model
        ]);
    }
}
