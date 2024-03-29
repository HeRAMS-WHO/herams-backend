<?php

declare(strict_types=1);

namespace herams\api\controllers\surveyResponse;

use herams\api\models\NewSurveyResponse;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\interfaces\CommandHandlerInterface;
use herams\common\jobs\UpdateFacilityDataJob;
use herams\common\models\SurveyResponse;
use herams\common\values\DatetimeValue;
use herams\common\values\FacilityId;
use yii\base\Action;
use yii\helpers\Url;


use yii\web\Request;
use yii\web\Response;

final class Create extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        ModelValidator $modelValidator,
        SurveyResponseRepository $surveyResponseRepository,
        Request $request,
        Response $response,
        CommandHandlerInterface $commandHandler,
        FacilityRepository $facilityRepository,
    ) {
        $requestData = $request->bodyParams;
        $commonField = CommonFieldsInTables::forCreatingHydratation();
        $requestData = [...$requestData, ...$commonField];
        $model = new NewSurveyResponse();
        $requestData['created_date'] = $requestData['createdDate'];
        $modelHydrator->hydrateFromJsonDictionary($model, $requestData);
        // Our model is now hydrated, we should validate it.
        /**
         * @psalm-assert FacilityId $model->facilityId
         */
        if (! $modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
        }

        // Todo: should we move this to an event that is triggered from the repository?
        $updateFacilityJob = new UpdateFacilityDataJob($model->facilityId);

        $facilityid = $model->facilityId;
        $facility = $facilityRepository->retrieveForUpdate($facilityid);
        $model->status = 'Validated';
        $model->response_type = $requestData['response_type'] ?? "admin";
        $model->createdBy = $commonField['createdBy'];
        $model->createdDate = new DatetimeValue($commonField['createdDate']);
        $model->lastModifiedDate = new DatetimeValue($commonField['lastModifiedDate']);
        $model->lastModifiedBy = $commonField['lastModifiedBy'];
        //$model->date_of_update = $facility->admin_data['date_of_update'] ?? null;
        $model->date_of_update = $requestData['data']['date_of_update'] ?? $requestData['data']['HSDU_DATE'] ?? $requestData['data']['SITUATION_DATE'] ?? null;
        $id = $surveyResponseRepository->save($model);
        $surveyResponse = SurveyResponse::findOne([
            'id' => $id,
        ]);
        $surveyId = $surveyResponse->facility->workspace->project->data_survey_id;
        $adminSuserveyId = $surveyResponse->facility->workspace->project->admin_survey_id;
        $surveyResponseRepository->updateSurveyDateToWorkspace($surveyId, $adminSuserveyId);

        // For now update the facility synchronously.
        $commandHandler->handle($updateFacilityJob);

        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/survey-response/view',
            'id' => $id,
        ]));

        return $response;
    }
}
