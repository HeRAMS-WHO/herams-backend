<?php

declare(strict_types=1);

namespace herams\common\domain\surveyResponse;

use herams\api\models\NewSurveyResponse;
use herams\api\models\UpdateSurveyResponse;
use herams\common\domain\facility\Facility;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\RecordInterface;
use herams\common\models\Permission;
use herams\common\models\SurveyResponse;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use herams\common\values\SurveyResponseId;
use prime\components\HydratedActiveDataProvider;
use prime\interfaces\AdminResponseForListInterface;
use prime\interfaces\surveyResponse\SurveyResponseForSurveyJsInterface;
use prime\models\forms\surveyResponse\CreateForm;
use prime\models\surveyResponse\SurveyResponseForSurveyJs;
use yii\data\DataProviderInterface;
use yii\data\Sort;
use yii\web\NotFoundHttpException;

class SurveyResponseRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ActiveRecordHydratorInterface $activeRecordHydrator,
        private ModelHydrator $hydrator,
    ) {
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

        return new SurveyResponseId($record->id);
    }

    public function save(NewSurveyResponse $model): SurveyResponseId
    {
        $record = new SurveyResponse();
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);

        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
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
            'created_at' => SORT_DESC,
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
            'created_at' => SORT_DESC,
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
                'created_at' => SORT_DESC,
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
        $record->date_of_update = $request['data']['date_of_update'] ?? $record->date_of_update;
        $record->status =  $record->status ?? 'Validated';
        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        $record->update();

        $suserveyId = $record->facility->workspace->project->data_survey_id;
        $adminSuserveyId = $record->facility->workspace->project->admin_survey_id;
        $this->updateSurveyDateToWorkspace($suserveyId, $adminSuserveyId);
        if ($record->date_of_update > $facility->date_of_update){
            $facility->date_of_update = $record->date_of_update;
            $facility->update();
        }
    }
    public function deleteSurveyResponse(UpdateSurveyResponse $model): void
    {
        $record = SurveyResponse::findOne($model->id);
        $record->status = 'Deleted';
        $record->update();
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
}
