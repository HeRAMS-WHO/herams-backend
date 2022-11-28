<?php

declare(strict_types=1);

namespace herams\api\controllers\facility;

use herams\common\domain\facility\NewFacility;
use herams\api\models\NewSurveyResponse;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
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
