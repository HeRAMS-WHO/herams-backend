<?php


namespace prime\controllers\project;

use prime\models\ar\Project;
use prime\models\search\Workspace as WorkspaceSearch;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Workspaces extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        User $user,
        Request $request,
        int $id
    ) {
        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));
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
