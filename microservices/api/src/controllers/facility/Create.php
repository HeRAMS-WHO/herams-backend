<?php

declare(strict_types=1);

namespace herams\api\controllers\facility;

use herams\api\models\NewSurveyResponse;
use herams\common\domain\facility\Facility;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\values\DatetimeValue;
use herams\common\values\FacilityId;
use herams\common\values\SurveyResponseId;
use herams\common\values\WorkspaceId;
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
        $data = [...$request->bodyParams];
        $data['data']['date_of_update'] = $request->bodyParams['data']['HSDU_DATE'];
        $data['data'] = [...$data['data'], ...CommonFieldsInTables::forCreatingHydratation()];
        $data = [
            ...$request->bodyParams,
            ...CommonFieldsInTables::forCreatingHydratation(),
        ];
        $data['adminData'] = $data['data'];
        $data['situationDate'] = [];
        $request->setBodyParams($data);
        $requestData = $request->bodyParams;

        $facility = new Facility();
        $facility->situation_data = [];
        $facility->admin_data = $data['data'];
        $facility->workspace_id = $data['workspaceId'];
        $facility->date_of_update = $data['data']['HSDU_DATE'];
        $facility->created_date = $data['createdDate'];
        $facility->created_by = $data['createdBy'];
        $facility->last_modified_date = $data['lastModifiedDate'];
        $facility->last_modified_by = $data['lastModifiedBy'];
        $facility->save();

        $facilityId = new FacilityId($facility->id);
        $projectId = $workspaceRepository->getProjectId(new WorkspaceId($facility->workspace_id));
        $facilityModel = $facilityRepository->retrieveForUpdate($facilityId);

        $responseRecord = new NewSurveyResponse();
        $responseRecord->surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $responseRecord->facilityId = $facilityId;
        $modelHydrator->hydrateFromJsonDictionary(
            $responseRecord,
            $data
        );

        $responseRecord->status = 'Validated';
        $responseRecord->date_of_update = $requestData['data']['HSDU_DATE'] ?? null;
        $responseRecord->response_type = 'admin';
        $responseRecord->createdBy = $data['createdBy'];
        $responseRecord->createdDate = new DatetimeValue($data['createdDate']);
        $responseRecord->lastModifiedBy = $data['lastModifiedBy'];
        $responseRecord->lastModifiedDate = new DatetimeValue($data['lastModifiedDate']);
        $surveyResponseId = $surveyResponseRepository->save($responseRecord);
        $surveyResponseId = new SurveyResponseId($surveyResponseId->getValue());
        $surveyResponseRepository->propagateSurveysResponses($surveyResponseId);
        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/facility/view',
            'id' => $facilityId,
        ]));

        return $response;
    }
}
