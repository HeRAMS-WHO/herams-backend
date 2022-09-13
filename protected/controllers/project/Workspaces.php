<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\models\search\Workspace as WorkspaceSearch;
use prime\values\ProjectId;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;
use function iter\toArray;

class Workspaces extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        BreadcrumbService $breadcrumbService,
        User $user,
        AccessCheckInterface $accessCheck,
        Request $request,
        int $id
    ) {
        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $projectId = new ProjectId($id);
        $project = Project::findOne([
            'id' => $id,
        ]);
        $accessCheck->requirePermission($project, Permission::PERMISSION_LIST_WORKSPACES);
        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );
        $workspaceSearch = new WorkspaceSearch($project, $user->identity);
        $workspaceProvider = $workspaceSearch->search($request->queryParams);
        return $this->controller->render('workspaces', [
            'workspaceSearch' => $workspaceSearch,
            'workspaceProvider' => $workspaceProvider,
            'project' => $project,
            'projectId' => $projectId,
            'userComponent' => $user,
        ]);
    }
}
