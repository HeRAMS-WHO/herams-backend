<?php


namespace prime\controllers\project;


use app\queries\WorkspaceQuery;
use prime\models\ar\Project;
use prime\models\search\Workspace as WorkspaceSearch;
use yii\base\Action;
use yii\web\Request;

class Workspaces extends Action
{

    public function run(
        Request $request,
        int $id
    ) {
        $project = Project::loadOne($id);
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