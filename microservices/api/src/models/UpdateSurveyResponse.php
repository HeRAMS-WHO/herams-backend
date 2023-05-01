<?php

declare(strict_types=1);

namespace herams\api\models;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\attributes\Field;
use herams\common\values\FacilityId;
use herams\common\values\SurveyId;
use herams\common\models\RequestModel;
use herams\common\values\SurveyResponseId;
use yii\validators\RequiredValidator;
use herams\common\helpers\LocalizedString;

class UpdateSurveyResponse extends RequestModel
{


    public function __construct(
        public readonly SurveyResponseId $id
    ) {
        parent::__construct();
    }

   // public LocalizedString|null $response_type = null;

    public RecordInterface|null $data = null;

    public function rules(): array
    {
        return [
            [['data'], RequiredValidator::class],
        ];
    }
}
