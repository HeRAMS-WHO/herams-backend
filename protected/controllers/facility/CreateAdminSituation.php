<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;
use prime\widgets\survey\Survey;
use Yii;
use yii\web\Response;

final class CreateAdminSituation extends FrontendAction
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        int $id
    ) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);
        $surveyJS = new Survey();
        $surveyJS->withConfig($survey->getConfig())
            ->withDataRoute([
                '/api/facility/latest-admin-situation',
                'id' => $facilityId,
            ], ['data'])
            ->withExtraData([
                'facilityId' => $facilityId,
                'surveyId' => $survey->getId(),
                'response_type' => 'admin',
            ])
            ->withSubmitRoute([
                'update-situation',
                'id' => $facilityId,
            ])
            ->withProjectId($projectId)
            ->withSubmitRoute([
                'api/survey-response/create',
            ])
            ->withRedirectRoute([
                'facility/admin-responses',
                'id' => $facilityId,
            ])
            ->withServerValidationRoute([
                'api/facility/validate-situation',
                'id' => $facilityId,

            ])
            ->deleteDate()
            ->setSurveySettings();
        $surveySettings = $surveyJS->getSurveySettings();
        $surveyHaveToDeleteData = $surveyJS->getHaveToDeleteData();

        return [
            'settings' => $surveySettings,
            'haveToDeleteData' => $surveyHaveToDeleteData,
        ];
    }
}
