<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use yii\base\Action;
use function iter\toArray;

final class Create extends Action
{
    public function run(
        AccessCheckInterface $accessCheck,
        SurveyRepository $surveyRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        BreadcrumbService $breadcrumbService,
        int $workspaceId
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspaceId = new WorkspaceId($workspaceId);
        $accessCheck->requirePermission($workspaceId, Permission::PERMISSION_CREATE_FACILITY);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);

        return $this->controller->render('create', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'survey' => $surveyRepository->retrieveForSurveyJs($surveyId),
        ]);
    }
}
