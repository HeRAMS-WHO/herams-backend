<?php


namespace prime\controllers\project;


use prime\models\ar\Project;
use prime\models\search\Workspace as WorkspaceSearch;
use yii\base\Action;
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
        $workspaceSearch = new WorkspaceSearch($project->id);

        $workspaceProvider = $workspaceSearch->search($request->queryParams);

        return $this->controller->render('workspaces', [
            'workspaceSearch' => $workspaceSearch,
            'workspaceProvider' => $workspaceProvider,
            'project' => $project
        ]);
    }
}