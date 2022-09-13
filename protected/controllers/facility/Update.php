<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\Controller;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use yii\base\Action;

class Update extends Action
{
    public function run(
        SurveyRepository $surveyRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        FacilityRepository $facilityRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);

        return $this->controller->render('update', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'id' => $facilityId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $surveyRepository->retrieveForSurveyJs($surveyId),
        ]);
    }
}
