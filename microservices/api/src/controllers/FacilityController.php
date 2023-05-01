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
use herams\common\domain\facility\FacilityRepository;
use herams\common\values\FacilityId;
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

        //print_r($surveyResponse); exit;
        return $surveyResponse;
    }
 
    public function actionSaveSituation(        
        SurveyResponseRepository $surveyResponseRepository,
        FacilityRepository $facilityRepository,
        ModelHydrator $modelHydrator,
        Request $request,
        Response $response,
        int $id
        ) {
        $surveyResponseId = new SurveyResponseId($id);
        
        $requestData =  $request->bodyParams;
        $model = new UpdateSurveyResponse($surveyResponseId);
        $modelHydrator->hydrateFromJsonDictionary($model, $requestData);
        \Yii::debug($request->bodyParams);
        if (! $model->validate()) {
            $response->setStatusCode(422);
            return $model->errors;
        }
        $facility = $facilityRepository->retrieveForUpdate(new FacilityId($requestData['facilityId']));
        $surveyResponseRepository->updateSurveyResponse($model, $requestData, $facility);
    }


}
