<?php

declare(strict_types=1);

namespace herams\api\models;

use herams\common\models\RequestModel;
use herams\common\values\SurveyId;
use yii\validators\RequiredValidator;

final class NewSurvey extends RequestModel
{
    public null|array $config = null;
    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class]
        ];
    }
}
