<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use prime\components\Controller;
use yii\base\Action;

class View extends Action
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

        return $this->controller->render('view', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'id' => $facilityId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $surveyRepository->retrieveForSurveyJs($surveyId),
        ]);
    }
}
