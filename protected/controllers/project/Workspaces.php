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
        User $user,
        AuthManager $abacManager,
        int $id
    ) {
//        \Yii::$app->db->enableLogging = false;

        foreach($abacManager->getRepository()->search($abacManager->resolveSubject($user->identity), null, null) as $grant)
        {
            $grant;
        }
        \Yii::$app->db->enableLogging = true;
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