<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\components\HydratedActiveDataProvider;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ResponseForList as ResponseForListInterface;
use prime\interfaces\surveyResponse\SurveyResponseForSurveyJsInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\SurveyResponse;
use prime\models\forms\surveyResponse\CreateForm;
use prime\models\response\AdminResponseForList;
use prime\models\response\ResponseForList;
use prime\models\surveyResponse\SurveyResponseForSurveyJs;
use prime\values\FacilityId;
use prime\values\SurveyId;
use prime\values\SurveyResponseId;
use yii\data\DataProviderInterface;
use yii\web\NotFoundHttpException;

class SurveyResponseRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
    ) {
    }

    public function create(
        CreateForm $model
    ): SurveyResponseId {
        $record = new SurveyResponse();
        $record->survey_id = $model->getSurveyId()->getValue();
        $record->facility_id = $model->getFacilityId()->getValue();
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
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
        $facility = Facility::findOne(['id' => $facilityId]);
        if (!$facility) {
            throw new NotFoundHttpException('No such facility.');
        }

        $adminSurveyId = $facility->workspace->project->data_survey_id;

        $surveyResponseQuery = SurveyResponse::find()->andWhere(['facility_id' => $facilityId, 'survey_id' => $adminSurveyId])->orderBy(['created_at' => SORT_DESC]);
        $surveyResponse = null;

        /** @var SurveyResponse $surveyResponseOption */
        foreach ($surveyResponseQuery->each() as $surveyResponseOption) {
            // TODO proper evaluation of the survey structure and use for situation update expression
            if ((bool) ($surveyResponseOption->data['useForSituationUpdate'] ?? true)) {
                $surveyResponse = $surveyResponseOption;
                break;
            }
        }

        if (!$surveyResponse) {
            return null;
        }

        return new SurveyResponseForSurveyJs(
            $surveyResponse->data,
            new SurveyResponseId($surveyResponse->id)
        );
    }

    public function retrieveAdminSurveyResponseForFacilityUpdate(FacilityId $facilityId): ?SurveyResponseForSurveyJsInterface
    {
        $facility = Facility::findOne(['id' => $facilityId]);
        if (!$facility) {
            throw new NotFoundHttpException('No such facility.');
        }

        $adminSurveyId = $facility->workspace->project->admin_survey_id;

        $surveyResponse = SurveyResponse::find()->andWhere(['facility_id' => $facilityId, 'survey_id' => $adminSurveyId])->orderBy(['created_at' => SORT_DESC])->one();

        if (!$surveyResponse) {
            return null;
        }

        return new SurveyResponseForSurveyJs(
            $surveyResponse->data,
            new SurveyResponseId($surveyResponse->id)
        );
    }

    public function searchAdminInFacility(FacilityId $facilityId): DataProviderInterface
    {
        $facility = Facility::findOne(['id' => $facilityId->getValue()]);
        $this->accessCheck->checkPermission($facility, Permission::PERMISSION_LIST_ADMIN_RESPONSES);
        $adminSurveyId = $facility->workspace->project->admin_survey_id;
        $query = SurveyResponse::find()->andWhere(['facility_id' => $facilityId, 'survey_id' => $adminSurveyId]);

        return new HydratedActiveDataProvider(
            static function (SurveyResponse $response): \prime\interfaces\AdminResponseForListInterface {
                return new AdminResponseForList($response);
            },
            [
                'sort' => [
                    'attributes' => [
                        ResponseForList::ID,
                        ResponseForList::DATE_OF_UPDATE => [
                            'asc' => ['created_at' => SORT_ASC],
                            'desc' => ['created_at' => SORT_DESC],
                            'default' => SORT_DESC,
                        ]
                    ]
                ],
                'query' => $query,
                'pagination' => false,
            ]
        );
    }

    public function searchDataInFacility(FacilityId $facilityId): DataProviderInterface
    {
        $facility = Facility::findOne(['id' => $facilityId->getValue()]);
        $this->accessCheck->checkPermission($facility, Permission::PERMISSION_LIST_DATA_RESPONSES);
        $dataSurveyId = $facility->workspace->project->data_survey_id;
        $query = SurveyResponse::find()->andWhere(['facility_id' => $facilityId, 'survey_id' => $dataSurveyId]);

        return new HydratedActiveDataProvider(
            static function (SurveyResponse $response): ResponseForListInterface {
                return new ResponseForList($response);
            },
            [
                'sort' => [
                    'attributes' => [
                        'id',
                        'dateOfUpdate' => [
                            'asc' => ['date' => SORT_ASC],
                            'desc' => ['date' => SORT_DESC],
                            'default' => SORT_DESC,
                        ]
                    ]
                ],
                'query' => $query,
                'pagination' => false,
            ]
        );
    }
}
