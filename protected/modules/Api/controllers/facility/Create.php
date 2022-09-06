<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\facility;

use prime\helpers\ModelHydrator;
use prime\helpers\ModelValidator;
use prime\modules\Api\models\NewFacility;
use prime\modules\Api\models\NewSurveyResponse;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyResponseRepository;
use prime\repositories\WorkspaceRepository;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

final class Create extends Action
{
    public function run(
        Request $request,
        ModelHydrator $modelHydrator,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        ModelValidator $modelValidator,
        Response $response,
        SurveyResponseRepository $surveyResponseRepository,
        FacilityRepository $facilityRepository
    ): Response {
        $facility = new NewFacility();

        $modelHydrator->hydrateFromJsonDictionary($facility, $request->bodyParams);

        if (! $modelValidator->validateModel($facility)) {
            return $modelValidator->renderValidationErrors($facility, $response);
        }

        $projectId = $workspaceRepository->getProjectId($facility->workspaceId);
        $facilityId = $facilityRepository->create($facility);

        $responseRecord = new NewSurveyResponse();
        $responseRecord->surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $responseRecord->facilityId = $facilityId;
        $modelHydrator->hydrateFromJsonDictionary($responseRecord, $request->bodyParams);
        $surveyResponseRepository->save($responseRecord);

        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/facility/view',
            'id' => $facilityId,
        ]));

        return $response;
    }
}
