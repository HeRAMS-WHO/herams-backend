<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\models\UpdateSurvey;
use herams\common\domain\survey\SurveyRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\values\SurveyId;
use yii\web\Request;
use yii\web\Response;

class SurveyController extends Controller
{
    public function actionIndex(SurveyRepository $surveyRepository)
    {
        return $surveyRepository->retrieveAll();
    }

    public function actionView(SurveyRepository $surveyRepository, int $id)
    {
       return $surveyRepository->retrieveForUpdate(new SurveyId($id));
    }

    public function actionUpdate(
        ModelHydrator $modelHydrator,
        Request $request,
        ModelValidator $modelValidator,
        SurveyRepository $surveyRepository,
        Response $response,
        int $id
    ) {
        $model = new UpdateSurvey(new SurveyId($id));
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams['data']);

        if (!$modelValidator->validateModel($model)) {
            return $modelValidator->renderValidationErrors($model, $response);
        }

        $surveyRepository->save($model);
        return $response;
    }
}
