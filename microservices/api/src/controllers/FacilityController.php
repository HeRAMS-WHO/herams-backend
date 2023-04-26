<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\facility\AdminResponses;
use herams\api\controllers\facility\Create;
use herams\api\controllers\facility\DataResponses;
use herams\api\controllers\facility\ValidateNew;
use herams\api\controllers\facility\View;


use herams\common\values\SurveyResponseId;
use herams\common\models\SurveyResponse;
use yii\web\Request;
use yii\web\Response;
use herams\common\helpers\ModelHydrator;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\api\models\UpdateSurveyResponse;

final class FacilityController extends Controller
{
    public function actions()
    {
        return [
            'create' => Create::class,
            'view' => View::class,
            'validate' => ValidateNew::class,
            'data-responses' => DataResponses::class,
            'admin-responses' => AdminResponses::class,
        ];
    }

    public function actionViewSituation(int $id) {
        $surveyResponseId = new SurveyResponseId($id);
        $data = 
        $surveyResponse = SurveyResponse::findOne([
            'id' => $surveyResponseId,
        ]);
        if (! $surveyResponse) {
            return null;
        }

        return $surveyResponse;
    }
 
    public function actionSaveSituation(        
        SurveyResponseRepository $surveyResponseRepository,
        ModelHydrator $modelHydrator,
        Request $request,
        Response $response,
        int $id
        ) {
        $surveyResponseId = new SurveyResponseId($id);
        //$model = new SurveyResponse($surveyResponseId);

        $model = new UpdateSurveyResponse($surveyResponseId);
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams);
        \Yii::debug($request->bodyParams);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }

        $surveyResponseRepository->updateSurveyResponse($model);
    }

    public function actionViewAdminSituation(int $id) {
        $surveyResponseId = new SurveyResponseId($id);
        $data = 
        $surveyResponse = SurveyResponse::findOne([
            'id' => $surveyResponseId,
        ]);
        if (! $surveyResponse) {
            return null;
        }

        return $surveyResponse;
    }
 
    public function actionSaveAdminSituation(        
        SurveyResponseRepository $surveyResponseRepository,
        ModelHydrator $modelHydrator,
        Request $request,
        Response $response,
        int $id
        ) {
        $surveyResponseId = new SurveyResponseId($id);
        //$model = new SurveyResponse($surveyResponseId);

        $model = new UpdateSurveyResponse($surveyResponseId);
        $modelHydrator->hydrateFromJsonDictionary($model, $request->bodyParams);
        \Yii::debug($request->bodyParams);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }

        $surveyResponseRepository->updateSurveyResponse($model);
    }

}
