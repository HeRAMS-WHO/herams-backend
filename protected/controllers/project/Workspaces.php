<?php


namespace prime\controllers\project;

use prime\models\ar\Project;
use prime\models\search\Workspace as WorkspaceSearch;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Workspaces extends Action
{
    public function run(
        Request $request,
        int $id
    ) {
        $this->controller->layout = 'admin';
        $project = Project::findOne(['id' => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }
        $workspaceSearch = new WorkspaceSearch($project);
        $workspaceProvider = $workspaceSearch->search($request->queryParams);
        return $this->controller->render('workspaces', [
            'workspaceSearch' => $workspaceSearch,
            'workspaceProvider' => $workspaceProvider,
            'project' => $project
        ]);
    }
}
