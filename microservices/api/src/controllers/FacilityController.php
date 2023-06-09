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
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
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
 
    public function actionLatestSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        int $id
    ) {
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveDataSurveyId($projectId);

        $surveyResponse = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $surveyId
        ])->andWhere([
        'or',
           ['!=', 'status', 'Deleted'],
           ['IS', 'status', null]
        ])->orderBy([
            'date_of_update' => SORT_DESC,
            'id'=> SORT_DESC
          ])->limit(1)->one();

        if (! $surveyResponse) {
            $surveyResponse['data'] =[];
            return $surveyResponse['data'];
        }
        $response['data'] =  $surveyResponse->data;
        $response['data']['date_of_update'] = null;
        $surveyResponse->data = $response['data'];
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

    public function actionDeleteFacility( 
        FacilityRepository $facilityRepository,
        Request $request,
        Response $response,
        int $id
        ) {
       
        $facilityId = new FacilityId($id);
        $facilityRepository->deleteFacility($facilityId);
    }

    public function actionLatestAdminSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        int $id
    ) {
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);

        $surveyResponse = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $surveyId
        ])->andWhere([
        'or',
           ['!=', 'status', 'Deleted'],
           ['IS', 'status', null]
        ])->orderBy([
            'date_of_update' => SORT_DESC,
            'id'=> SORT_DESC
          ])->limit(1)->one();

        if (! $surveyResponse) {
            $surveyResponse['data'] =[];
            return $surveyResponse['data'];
        }
        $response['data'] =  $surveyResponse->data;
        $response['data']['date_of_update'] = null;
        $surveyResponse->data = $response['data'];
        //print_r($surveyResponse); exit;
        return $surveyResponse;
    }
    public function actionValidateSituation(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        SurveyRepository $surveyRepository,
        ProjectRepository $projectRepository,
        Request $request,
        Response $response,
        int $id
    ) {

        $requestData =  $request->bodyParams;
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);

        $projectId = $workspaceRepository->getProjectId($workspaceId);
        if($requestData['response_type'] == 'admin'){
            $surveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        }else{
            $surveyId = $projectRepository->retrieveDataSurveyId($projectId);
        }

        $query = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $surveyId,
            'date_of_update' => $requestData['data']['date_of_update'],
        ])->andWhere([
        'or',
           ['!=', 'status', 'Deleted'],
           ['IS', 'status', null]
        ]);

        if(isset($requestData['response_id'])){
            $query->andWhere(['!=', 'id', $requestData['response_id']]);
        }
        $surveyResponse = $query->limit(1)->one();

        if ($surveyResponse) {
            return  ['errors' => ['date_of_update' => ['This Date of Update already taken']]];
        }
        return ['errors'=>[]];
    }

}
