<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\surveyResponse\SurveyResponseForSurveyJsInterface;
use prime\models\ar\Facility;
use prime\models\ar\SurveyResponse;
use prime\models\forms\surveyResponse\CreateForm;
use prime\models\surveyResponse\SurveyResponseForSurveyJs;
use prime\values\FacilityId;
use prime\values\SurveyId;
use prime\values\SurveyResponseId;
use yii\web\NotFoundHttpException;

class SurveyResponseRepository
{
    public function __construct(
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

    public function retrieveLastAdminSurveyResponseForFacility(FacilityId $facilityId): ?SurveyResponseForSurveyJsInterface
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
}
