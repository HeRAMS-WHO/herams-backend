<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\actions\FrontendAction;
use prime\components\BreadcrumbService;
use prime\objects\BreadcrumbCollection;
use prime\objects\enums\ProjectType;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\ResponseForLimesurveyRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use function iter\toArray;

class Responses extends FrontendAction
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        ResponseForLimesurveyRepository $responseForLimesurveyRepository,
        SurveyResponseRepository $surveyResponseRepository,
        SurveyRepository $surveyRepository,
        BreadcrumbService $breadcrumbService,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        $this->controller->view->breadcrumbCollection->mergeWith($breadcrumbService->retrieveForFacility($facilityId));

        if ($facilityRepository->isOfProjectType($facilityId, ProjectType::limesurvey())) {
            $dataProvider = $responseForLimesurveyRepository->searchInFacility($facilityId);
            $updateSituationUrl = [
                'copy-latest-response',
                'id' => $facility->getId(),
            ];
        } else {
            $dataProvider = $surveyResponseRepository->searchDataInFacility($facilityId);
            $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

            $projectId = $workspaceRepository->getProjectId($workspaceId);
            $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
            $variableSet = $surveyRepository->retrieveSimpleVariableSet($surveyId);


            $updateSituationUrl = [
                'update-situation',
                'id' => $facility->getId(),
            ];
        }

        return $this->controller->render(
            'responses',
            [
                'responseProvider' => $dataProvider,
                'facility' => $facility,
                'updateSituationUrl' => $updateSituationUrl,
            ]
        );
    }
}
