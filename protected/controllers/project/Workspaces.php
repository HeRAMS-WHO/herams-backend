<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use herams\common\values\ProjectId;
use prime\actions\FrontendAction;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\models\ar\read\Project;
use yii\web\User;
use function iter\toArray;

final class Workspaces extends FrontendAction
{
    public array $dataRoute = [
        'api/project/workspaces',
    ];

    public function run(
        BreadcrumbService $breadcrumbService,
        User $user,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $projectId = new ProjectId($id);
        $project = Project::findOne([
            'id' => $id,
        ]);
        $accessCheck->requirePermission($project, Permission::PERMISSION_LIST_WORKSPACES);

        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );
        return $this->render('workspaces', [
            'project' => $project,
            'projectId' => $projectId,
            'userComponent' => $user,
            'dataRoute' => [
                ...$this->dataRoute,
                'id' => $projectId,
            ],
        ]);
    }

    public function setTitle(string $title): void
    {
        $this->controller->view->title = $title;
    }
}
