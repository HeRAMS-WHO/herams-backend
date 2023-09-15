<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use herams\common\values\ProjectId;
use prime\actions\FrontendAction;
use prime\components\BreadcrumbService;
use prime\components\Controller;
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
        ProjectRepository $projectRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $projectId = new ProjectId($id);
        $project = $projectRepository->retrieveForRead($projectId);
        $accessCheck->requirePermission($project, PermissionOld::PERMISSION_LIST_WORKSPACES);

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
