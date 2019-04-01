<?php


namespace prime\controllers\project;


use app\queries\WorkspaceQuery;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use prime\models\search\Workspace as WorkspaceSearch;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\User;

class Workspaces extends Action
{

    public function run(
        Request $request,
        User $user,
        int $id
    ) {
        $project = Project::loadOne($id);
        if (! (
            $user->can(Permission::PERMISSION_READ, $project)
            || $user->can(Permission::PERMISSION_INSTANTIATE, $project)
            || $user->can(Permission::PERMISSION_WRITE, $project)
        )
        ) {
            throw new ForbiddenHttpException();
        }
        $workspaceSearch = new WorkspaceSearch($project->id, [
            'queryCallback' => function(WorkspaceQuery $query) {
                return $query->readable();
            }
        ]);

        $workspaceProvider = $workspaceSearch->search($request->queryParams);

        return $this->controller->render('workspaces', [
            'workspaceSearch' => $workspaceSearch,
            'workspaceProvider' => $workspaceProvider,
            'project' => $project
        ]);
    }
}