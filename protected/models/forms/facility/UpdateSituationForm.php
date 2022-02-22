<?php

declare(strict_types=1);

namespace prime\models\forms\facility;

use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\objects\LanguageSet;
use prime\traits\DisableYiiLoad;
use prime\values\FacilityId;
use yii\base\Model;
use yii\validators\RequiredValidator;

final class UpdateSituationForm extends Model
{
    use DisableYiiLoad;

    public null|array $data = null;

    public function __construct(
        private FacilityId $facilityId,
        private LanguageSet $languages,
        private SurveyForSurveyJsInterface $survey,
    ) {
        parent::__construct();
    }

    public function getFacilityId(): FacilityId
    {
        return $this->facilityId;
    }

    public function getLanguages(): LanguageSet
    {
        return $this->languages;
    }

    public function getSurvey(): SurveyForSurveyJsInterface
    {
        return $this->survey;
    }

    /**
     * This validates only the absolutely necessary requirements
     * @return array
     */
    public function rules(): array
    {
        return [
            [['data'], RequiredValidator::class],
        ];
    }
}
