<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\base\Action;
use yii\web\Response;

class DeleteWorkspaces extends Action
{

    public function run(
        Response  $response,
        NotificationService $notificationService,
        AccessCheckInterface $accessCheck,
        int $id
    ): Response {
        $project = Project::findOne(['id' => $id]);
        $accessCheck->requirePermission($project, Permission::PERMISSION_DELETE_ALL_WORKSPACES);

        $skipped = 0;
        $success = 0;
        foreach ($project->workspaces as $workspace) {
            if (!$workspace->delete()) {
                $skipped++;
            } else {
                $success++;
            }
        }

        $notificationService->info(\Yii::t('app', 'Deleted {n} workspaces, {m} workspaces could not be removed', [
            'n' => $success,
            'm' => $skipped
        ]));
        return $this->controller->redirect(['project/workspaces', 'id' => $project->id]);
    }
}
