<?php

declare(strict_types=1);

namespace herams\api\models;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\attributes\Field;
use herams\common\values\FacilityId;
use herams\common\values\SurveyId;
use yii\base\Model;
use yii\validators\RequiredValidator;

class NewSurveyResponse extends Model
{
    #[Field('survey_id')]
    public SurveyId|null $surveyId = null;

    #[Field('facility_id')]
    public FacilityId|null $facilityId = null;

    public RecordInterface|null $data = null;

    public $status;
    public $date_of_update;
    public $response_type;

    public function rules(): array
    {
        return [
            [['surveyId', 'facilityId', 'data'], RequiredValidator::class],
        ];
    }
}
