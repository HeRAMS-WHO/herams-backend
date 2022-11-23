<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\values\FacilityId;
use prime\actions\FrontendAction;
use prime\components\NotificationService;
use yii\web\Request;
use yii\web\Response;

final class UpdateSituation extends FrontendAction
{
    public function run(
        FacilityRepository $facilityRepository,
        ModelHydrator $modelHydrator,
        NotificationService $notificationService,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        Request $request,
        Response $response,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $survey = $surveyRepository->retrieveForSurveyJs($surveyId);

        return $this->render('updateSituation', [
            'projectId' => $projectId,
            'workspaceId' => $workspaceId,
            'facilityId' => $facilityId,
            'tabMenuModel' => $facilityRepository->retrieveForTabMenu($facilityId),
            'survey' => $survey,
        ]);
    }
}
