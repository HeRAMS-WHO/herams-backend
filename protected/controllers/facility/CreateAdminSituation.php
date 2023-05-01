<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;

final class CreateAdminSituation extends FrontendAction
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        int $id
    ) {
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        return $this->render('admin-situation/create', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'facilityId' => $facilityId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $survey,
        ]);
    }
}
