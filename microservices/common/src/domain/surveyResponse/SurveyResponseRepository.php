<?php

declare(strict_types=1);

namespace herams\common\domain\surveyResponse;

use herams\api\models\NewSurveyResponse;
use herams\api\models\UpdateSurveyResponse;
use herams\common\domain\facility\Facility;
use herams\common\domain\facility\FacilityTier;
use herams\common\domain\facility\HSDUStateEnum;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\RecordInterface;
use herams\common\models\Permission;
use herams\common\models\Project;
use herams\common\models\Survey;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\utils\tools\SurveyParserClean;
use herams\common\values\DatetimeValue;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use herams\common\values\SurveyResponseId;
use prime\interfaces\surveyResponse\SurveyResponseForSurveyJsInterface;
use prime\models\forms\surveyResponse\CreateForm;
use prime\models\surveyResponse\SurveyResponseForSurveyJs;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class SurveyResponseRepository
{
    /**
     * @param AccessCheckInterface $accessCheck
     * @param ActiveRecordHydratorInterface $activeRecordHydrator
     * @param ModelHydrator $hydrator
     * @param SurveyParserClean $surveyParserClean
     */
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ActiveRecordHydratorInterface $activeRecordHydrator,
        private ModelHydrator $hydrator,
        private SurveyParserClean $surveyParserClean
    ) {
    }
    public function deleteAll(array $condition): void {
        SurveyResponse::deleteAll($condition);
    }
    public function create(
        CreateForm $model
    ): SurveyResponseId {

        $record = new SurveyResponse();
        $record->survey_id = $model->getSurveyId()->getValue();
        $record->facility_id = $model->getFacilityId()->getValue();
        $this->hydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        $surveyId = new SurveyId($record->id);
        $this->propagateSurveysResponses($surveyId);

        return new SurveyResponseId($record->id);
    }

    public function save(NewSurveyResponse $model): SurveyResponseId
    {
        $record = new SurveyResponse();
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);

        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        $surveyResponseId = new SurveyResponseId($record->id);
        $this->propagateSurveysResponses($surveyResponseId);
        return new SurveyResponseId($record->id);
    }

    public function createFormModel(
        SurveyId $surveyId,
        FacilityId $facilityId = null
    ): CreateForm {
        return new CreateForm(
            $surveyId,
            $facilityId
        );
    }

    public function retrieveDataSurveyResponseForFacilitySituationUpdate(FacilityId $facilityId): ?SurveyResponseForSurveyJsInterface
    {
        $facility = Facility::findOne([
            'id' => $facilityId,
        ]);
        if (! $facility) {
            throw new NotFoundHttpException('No such facility.');
        }

        $adminSurveyId = $facility->workspace->project->data_survey_id;

        $surveyResponseQuery = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $adminSurveyId,
        ])->orderBy([
            'created_date' => SORT_DESC,
        ]);
        $surveyResponse = null;

        /** @var SurveyResponse $surveyResponseOption */
        foreach ($surveyResponseQuery->each() as $surveyResponseOption) {
            // TODO proper evaluation of the survey structure and use for situation update expression
            if ((bool) ($surveyResponseOption->data['useForSituationUpdate'] ?? true)) {
                $surveyResponse = $surveyResponseOption;
                break;
            }
        }

        if (! $surveyResponse) {
            return null;
        }

        return new SurveyResponseForSurveyJs(
            $surveyResponse->data,
            new SurveyId($surveyResponse->survey_id),
            new SurveyResponseId($surveyResponse->id),
            new ProjectId($surveyResponse->facility->workspace->project_id)
        );
    }

    public function retrieveAdminSurveyResponseForFacilityUpdate(FacilityId $facilityId): ?SurveyResponseForSurveyJsInterface
    {
        $facility = Facility::findOne([
            'id' => $facilityId,
        ]);
        if (! $facility) {
            throw new NotFoundHttpException('No such facility.');
        }

        $adminSurveyId = $facility->workspace->project->admin_survey_id;

        $surveyResponse = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $adminSurveyId,
        ])->orderBy([
            'created_date' => SORT_DESC,
        ])->one();

        if (! $surveyResponse) {
            return null;
        }

        return new SurveyResponseForSurveyJs(
            $surveyResponse->data,
            new SurveyId($surveyResponse->survey_id),
            new SurveyResponseId($surveyResponse->id),
            new ProjectId($surveyResponse->facility->workspace->project_id)
        );
    }

    public function retrieve(SurveyResponseId $id): SurveyResponseForSurveyJsInterface
    {
        $surveyResponse = SurveyResponse::find()->andWhere([
            'id' => $id,
        ])->one();
        $this->accessCheck->requirePermission($surveyResponse, Permission::PERMISSION_READ);
        return new SurveyResponseForSurveyJs(
            $surveyResponse->data,
            new SurveyId($surveyResponse->survey_id),
            $id,
            new ProjectId($surveyResponse->facility->workspace->project_id)
        );
    }

    public function getLatestAdminResponseForFacility(FacilityId $facilityId): null|RecordInterface
    {
        $facility = Facility::findOne([
            'id' => $facilityId->getValue(),
        ]);
        $this->accessCheck->checkPermission($facility, Permission::PERMISSION_LIST_ADMIN_RESPONSES);
        $adminSurveyId = $facility->workspace->project->admin_survey_id;
        $query = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $adminSurveyId,
        ])
            // TODO: use the date of update here.
            ->orderBy([
                'created_date' => SORT_DESC,
            ])
            ->limit(1);
        return $query->one();
    }

    public function getLatestDataResponseForFacility(FacilityId $facilityId): null|RecordInterface
    {
        $facility = Facility::findOne([
            'id' => $facilityId->getValue(),
        ]);
        $this->accessCheck->checkPermission($facility, Permission::PERMISSION_LIST_DATA_RESPONSES);

        $dataSurveyId = $facility->workspace->project->data_survey_id;
        $query = SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $dataSurveyId,
        ])
            // TODO: use the date of update here.
            ->orderBy([
                'created_at' => SORT_DESC,
            ])
            ->limit(1);
        return $query->one();
    }



    /**
     * @return SurveyResponse
     */
    public function retrieveDataInFacility(FacilityId $facilityId): array
    {
        $facility = Facility::findOne([
            'id' => $facilityId->getValue(),
        ]);
        $this->accessCheck->checkPermission($facility, Permission::PERMISSION_LIST_DATA_RESPONSES);
        return $this->retrieveData($facilityId, $facility->workspace->project->getDataSurveyId());
    }

    /**
     * @return SurveyResponse
     */
    private function retrieveData(FacilityId $facilityId, SurveyId $surveyId): array
    {
        return SurveyResponse::find()->andWhere([
            'facility_id' => $facilityId,
            'survey_id' => $surveyId
        ])->andWhere([
        'or',
           ['!=', 'status', 'Deleted'],
           ['IS', 'status', null]
        ])->orderBy([
            'date_of_update' => SORT_DESC,
          ])->all();

    }
    /**
     * @return SurveyResponse
     */
    public function retrieveAdminDataInFacility(FacilityId $facilityId): array
    {
        $facility = Facility::findOne([
            'id' => $facilityId->getValue(),
        ]);
        $this->accessCheck->checkPermission($facility, Permission::PERMISSION_LIST_ADMIN_RESPONSES);
        return $this->retrieveData($facilityId, $facility->workspace->project->getAdminSurveyId());
    }

    public function updateSurveyResponse(UpdateSurveyResponse $model, $request, $facility): void
    {
        $record = SurveyResponse::findOne([
            'id' => $model->id,
        ]);
        \Yii::debug($model->attributes);
        $record->response_type =  $request['response_type'];
        //$record->date_of_update = $facility->admin_data['date_of_update'] ?? $record->date_of_update;
        $record->date_of_update = $request['data']['date_of_update']
            ?? $request['data']['SITUATION_DATE']
            ?? $request['data']['HSDU_DATE'] ?? null;
        $record->status =  $record->status ?? 'Validated';
        $commondFields = CommonFieldsInTables::forUpdating();
        $record->lastModifiedDate = new DatetimeValue($commondFields['last_modified_date']);
        $record->lastModifiedBy = $commondFields['last_modified_by'];
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        $record->update();
        $surveyId = new SurveyResponseId($record->id);
        $this->propagateSurveysResponses($surveyId);
    }
    public function deleteSurveyResponse(UpdateSurveyResponse $model): void
    {
        $record = SurveyResponse::findOne($model->id);
        $record->status = 'Deleted';
        $record->update();
        $surveyResponseId = new SurveyResponseId($model->id->getValue());
        $this->propagateSurveysResponses($surveyResponseId);
        //return $this->redirect(\Yii::$app->request->referrer);
    }
    public function updateSurveyDateToWorkspace($surveyId,$adminSuserveyId){

        $surveyResponse = SurveyResponse::find()
        ->where(['survey_id' => $surveyId])
        ->orWhere(['survey_id' => $adminSuserveyId])
        ->orderBy('date_of_update DESC')->limit(1)->one();
        
        $surveyResponse->facility->workspace->date_of_update = $surveyResponse->date_of_update;

        $surveyResponse->facility->workspace->update();

    }
    public function propagateSurveysResponses(SurveyResponseId $surveyResponseId): void {
        $this->updateDateOnProject($surveyResponseId);
        $this->updateDatesOnFacility($surveyResponseId);
        $this->updateDateOnWorkspace($surveyResponseId);
        $this->updateAnswersOnFacility($surveyResponseId);
        $this->updateIfFacilityCanReceiveUpdates($surveyResponseId);
    }
    public function updateIfFacilityCanReceiveUpdates(SurveyResponseId $surveyResponseId): void {
        $surveyResponse = SurveyResponse::findOne(['id' => $surveyResponseId->getValue()]);
        $survey = Survey::findOne(['id' => $surveyResponse->survey_id]);
        $facility = Facility::findOne(['id' => $surveyResponse->facility_id]);
        $adminData = SurveyResponse::find()
            ->select('data')
            ->where(['!=', 'status', 'Deleted'])
            ->andWhere(['facility_id' => $surveyResponse->facility_id])
            ->andWhere(['response_type' => 'admin'])
            ->orderBy(['date_of_update' => SORT_DESC])
            ->one();
        $state = $adminData->data['HSDU_DATE'];
        $surveyData = Survey::findOne(['id' => $adminData->survey_id]);
        $hsduStatusData = $this->surveyParserClean::findQuestionInfo($survey, 'HSDU_STATUS');
        $statusInSurvey = $adminData->data['HSDU_STATUS'];
        $status = HSDUStateEnum::acceptUpdates;
        foreach($hsduStatusData['choices'] ?? [] as $statusInfo){
            if ($statusInfo['value'] === $statusInSurvey){
                $status = HSDUStateEnum::from((int)$statusInfo['hsdu_state']);
                break;
            }
        }
        $facility->can_receive_situation_update = $status === HSDUStateEnum::acceptUpdates;
        $facility->save();
    }
    public function updateAnswersOnFacility(
        SurveyResponseId $surveyResponseId

    ): void {
        $surveyResponse = SurveyResponse::findOne(['id' => $surveyResponseId->getValue()]);
        $survey = Survey::findOne(['id' => $surveyResponse->survey_id]);
        $facility = Facility::findOne(['id' => $surveyResponse->facility_id]);
        $adminData = SurveyResponse::find()
            ->select('data')
            ->where(['!=', 'status', 'Deleted'])
            ->andWhere(['facility_id' => $surveyResponse->facility_id])
            ->andWhere(['response_type' => 'admin'])
            ->orderBy(['date_of_update' => SORT_DESC])
            ->one();
        $situationData = SurveyResponse::find()
            ->select('data')
            ->where(['!=', 'status', 'Deleted'])
            ->andWhere(['facility_id' => $surveyResponse->facility_id])
            ->andWhere(['response_type' => 'situation'])
            ->orderBy(['date_of_update' => SORT_DESC])
            ->one();

        if (!is_null($situationData?->data)){
            $facility->situation_data = $situationData->data;
        }

        if (!is_null($adminData?->data)){
            $tierData = $this->surveyParserClean::findQuestionInfo($survey, 'HSDU_TYPE');
            $tier = 'Other';
            try {
                foreach($tierData['choices'] ?? [] as $tierInfo){
                    if ($tierInfo['value'] === ($adminData->data['HSDU_TYPE'] ?? $adminData?->data['HSDU_TYPE'] ?? $adminData->data['HSDU_TYPE'] ?? [])){
                        $tier = FacilityTier::from((int)$tierInfo['tier'])->label('en');
                        break;
                    }
                }
            }
            catch (\Exception $exception){
                $tier = 'Unknown';
            }

            $facility->admin_data = $adminData->data;
            try {
                $facility->latitude = $adminData->data['HSDU_COORDINATES']['HSDU_LATITUDE'];
                $facility->longitude = $adminData->data['HSDU_COORDINATES']['HSDU_LONGITUDE'];
            }
            catch (\Exception $exception){
                $facility->longitude = null;
                $facility->latitude = null;
            }
            catch (\Error $error){
                $facility->longitude = null;
                $facility->latitude = null;
            }

            $facility->tier = $tier;
        }
        $facility->save();
    }
    public function updateDatesOnFacility(SurveyResponseId $surveyResponseId): void {
        $survey = SurveyResponse::findOne(['id' => $surveyResponseId->getValue()]);
        $facility = Facility::findOne(['id' => $survey->facility_id]);
        $date_of_update = SurveyResponse::find()
            ->select('MAX(date_of_update)')
            ->where(['!=', "status", 'Deleted'])
            ->andWhere(['facility_id' => $survey->facility_id])
            ->orderBy('date_of_update DESC')
            ->scalar();
            //->scalar();
        $facility->date_of_update = $date_of_update;
        $commonField = CommonFieldsInTables::forUpdating();
        $facility->last_modified_by = $commonField['last_modified_by'];
        $facility->last_modified_date = $commonField['last_modified_date'];
        $facility->update();
    }
    public function updateDateOnProject(SurveyResponseId $surveyResponseId): void {
        $survey = SurveyResponse::findOne(['id' => $surveyResponseId->getValue()]);
        $facility = Facility::findOne(['id' => $survey->facility_id]);
        $workspace = Workspace::findOne(['id' => $facility->workspace_id]);
        $project = Project::findOne(['id'  => $workspace->project_id]);
        $query = new Query();
        $query->select(['MAX(prime2_survey_response.date_of_update)'])
            ->from('prime2_survey_response')
            ->innerJoin('prime2_facility', 'prime2_survey_response.facility_id = prime2_facility.id')
            ->innerJoin('prime2_workspace', 'prime2_workspace.id = prime2_facility.workspace_id')
            ->innerJoin('prime2_project', 'prime2_project.id = prime2_workspace.project_id')
            ->where(['prime2_project.id' => (int) $project->id]);
        $date_of_update = $query->scalar();
        $project->date_of_update = $date_of_update;
        $project->update(false);


    }

    public function updateDateOnWorkspace(SurveyResponseId $surveyResponseId): void {
        $surveyResponse = SurveyResponse::findOne(['id' => $surveyResponseId->getValue()]);
        $facility = Facility::findOne(['id' => $surveyResponse->facility_id]);

        $query = new Query();
        $workspaceTableName = Workspace::tableName();
        $facilityTableName = Facility::tableName();
        $surveyResponseTableName = SurveyResponse::tableName();

        $date_of_update = $query->select("MAX($surveyResponseTableName.date_of_update)")
            ->from($workspaceTableName)
            ->innerJoin($facilityTableName, "$facilityTableName.workspace_id = $workspaceTableName.id")
            ->innerJoin($surveyResponseTableName, "$facilityTableName.id = $surveyResponseTableName.facility_id")
            ->where([$workspaceTableName . '.id' => $facility->workspace_id])
            ->andWhere(['!=', "$surveyResponseTableName.status", 'Deleted'])
            ->scalar();
        $workspace = Workspace::findOne(['id' => $facility->workspace_id]);
        $workspace->date_of_update = $date_of_update;
        $workspace->update();
    }
}
