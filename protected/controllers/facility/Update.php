<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;

final class Update extends FrontendAction
{
    public function run(
        SurveyRepository $surveyRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        FacilityRepository $facilityRepository,
        int $id
    ) {
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);

        return $this->render('update', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'id' => $facilityId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $surveyRepository->retrieveForSurveyJs($surveyId),
        ]);
    }
}
