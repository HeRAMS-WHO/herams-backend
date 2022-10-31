<?php

declare(strict_types=1);

namespace herams\api\controllers\surveyResponse;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\helpers\ModelHydrator;
use prime\helpers\ModelValidator;
use prime\jobs\UpdateFacilityDataJob;
use herams\api\models\NewSurveyResponse;
use prime\repositories\SurveyResponseRepository;
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
        JobQueueInterface $jobQueue,
    ) {
        $model = new NewSurveyResponse();
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams);

        // Our model is now hydrated, we should validate it.
        /**
         * @psalm-assert FacilityId $model->facilityId
         */
        if (! $modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
        }

        $updateFacilityJob = new UpdateFacilityDataJob($model->facilityId);
        $jobQueue->putJob($updateFacilityJob);

        $id = $surveyResponseRepository->save($model);
        // For now update the facility synchronously.

        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/survey-response/view',
            'id' => $id,
        ]));

        return $response;
    }
}
