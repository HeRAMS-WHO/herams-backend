<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\Controller;
use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

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
            'survey' => $surveyRepository->retrieveForSurveyJs($surveyId)
        ]);
    }

}
