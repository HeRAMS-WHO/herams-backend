<?php
declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\ActiveQuery;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\WorkspaceQuery;
use prime\models\forms\project\SyncWorkspaces as SyncWorkspacesForm;
use yii\base\Action;
use yii\web\Request;

class SyncWorkspaces extends Action
{

    public function run(
        Request $request,
        NotificationService $notificationService,
        AccessCheckInterface $accessCheck,
        int $id
    ): string {
        /** @var Project|null $project */
        $project = Project::find()
            ->with(['workspaces' => static function (WorkspaceQuery $query) {
                $query->withFields(['latestUpdate']);
                $query->addSelect([
                    'latestUpdate' => \prime\models\ar\Response::find()
                        ->select('MAX(last_updated)')
                        ->where('workspace_id = prime2_workspace.id')
                        ->groupBy('workspace_id')
                ]);
                $query->orderBy(['latestUpdate' => SORT_DESC]);
            }])
            ->andWhere(['id' => $id])->one();
        $accessCheck->requirePermission($project, Permission::PERMISSION_ADMIN);

        $model = new SyncWorkspacesForm($project);
        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            return $this->controller->render('sync-workspaces-execute', ['project' => $project, 'model' => $model]);
        }
        return $this->controller->render('sync-workspaces-form', ['project' => $project, 'model' => $model]);
    }
}
