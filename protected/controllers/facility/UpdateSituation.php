<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;
use prime\components\BreadcrumbService;
use prime\widgets\survey\Survey;
use Yii;
use yii\web\Response;


final class UpdateSituation extends FrontendAction
{

    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        BreadcrumbService $breadcrumbService,
        int $id
    ) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);
        $this->controller->view->breadcrumbCollection->mergeWith($breadcrumbService->retrieveForFacility($facilityId));
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);
        $surveyJS = new Survey();
        $surveyJS->withConfig($survey->getConfig())
            ->withDataRoute([
                '/api/facility/latest-situation',
                'id' => $facilityId,
            ], ['data'])
            ->withExtraData([
                'facilityId' => $facilityId,
                'surveyId' => $survey->getId(),
                'response_type' => 'situation',
            ])
            ->withSubmitRoute([
                'update-situation',
                'id' => $facilityId,
            ])
            ->withProjectId($projectId)
            ->withSubmitRoute([
                'api/survey-response/create',
            ])
            ->withRedirectRoute(
                '/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update'
            )
            ->withServerValidationRoute([
                'api/facility/validate-situation',
                'id' => $facilityId,

            ])
            ->deleteDate()
            ->setSurveySettings();

        $settings = $surveyJS->getSurveySettings();
        $deleteData = $surveyJS->getHaveToDeleteData();

        return [
            'settings' => $settings,
            'deleteData' => $deleteData,
        ];
    }
}
