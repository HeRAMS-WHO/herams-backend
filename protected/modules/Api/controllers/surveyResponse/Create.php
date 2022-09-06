<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\surveyResponse;

use prime\helpers\ModelHydrator;
use prime\helpers\ModelValidator;
use prime\modules\Api\models\NewSurveyResponse;
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
        Response $response
    ) {
        $model = new NewSurveyResponse();
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams);

        // Our model is now hydrated, we should validate it.
        if (! $modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
        }

        $id = $surveyResponseRepository->save($model);
        $response->setStatusCode(201);
        $response->headers->add('Location', Url::to([
            '/api/survey-response/view',
            'id' => $id,
        ]));

        return $response;
    }
}
