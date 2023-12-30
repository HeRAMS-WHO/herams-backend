<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\widgets\survey\Survey;
use Yii;
use yii\base\Action;
use yii\web\Response;
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $workspaceId = new WorkspaceId($workspaceId);
        $accessCheck->requirePermission($workspaceId, PermissionOld::PERMISSION_CREATE_FACILITY);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $this->controller->view->breadcrumbCollection->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);

        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        $surveyJS = new Survey();
        $surveyJS->withConfig($survey->getConfig())
            ->withProjectId($projectId)
            ->withExtraData([
                'workspaceId' => $workspaceId,
            ])
            ->withSubmitRoute([
                '/api/facility/create',
            ])
            ->withServerValidationRoute([
                '/api/facility/validate',
                'workspace_id' => $workspaceId,
            ])
            ->withRedirectRoute([
                '/workspace/facilities',
                'id' => $workspaceId,
            ])->setSurveySettings();
        $surveySettings = $surveyJS->getSurveySettings();

        return [
            'settings' => $surveySettings,
        ];
    }
}
