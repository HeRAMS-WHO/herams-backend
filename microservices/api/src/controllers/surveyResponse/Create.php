<?php

declare(strict_types=1);

namespace herams\api\controllers\surveyResponse;

use herams\api\models\NewSurveyResponse;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\interfaces\CommandHandlerInterface;
use herams\common\jobs\UpdateFacilityDataJob;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;


use herams\common\values\FacilityId;
use herams\common\domain\facility\FacilityRepository;

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
        $model = new NewSurveyResponse();
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams);

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
        $model->status = 'Validatd';
        $model->survey_date = $facility->admin_data['date_of_update'] ?? null;
        $id = $surveyResponseRepository->save($model);

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
