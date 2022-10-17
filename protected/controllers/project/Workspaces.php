<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\User;
use function iter\toArray;

class Workspaces extends Action
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
        return $this->controller->render('workspaces', [
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
