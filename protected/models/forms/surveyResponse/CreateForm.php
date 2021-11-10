<?php

declare(strict_types=1);

namespace prime\models\forms\surveyResponse;

use prime\models\ar\SurveyResponse;
use prime\traits\DisableYiiLoad;
use prime\values\FacilityId;
use prime\values\SurveyId;
use yii\base\Model;
use yii\validators\RequiredValidator;

final class CreateForm extends Model
{
    use DisableYiiLoad;

    public array $data = [];

    public function __construct(
        private SurveyId $surveyId,
        private FacilityId|null $facilityId = null,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return SurveyResponse::labels();
    }

    public function getFacilityId(): FacilityId
    {
        return $this->facilityId;
    }

    public function getSurveyId(): SurveyId
    {
        return $this->surveyId;
    }

    public function rules(): array
    {
        return [
            [['data'], RequiredValidator::class],
        ];
    }
}
