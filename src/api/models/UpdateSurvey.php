<?php

declare(strict_types=1);

namespace herams\api\models;

use prime\models\RequestModel;
use prime\values\SurveyId;
use yii\validators\RequiredValidator;

final class UpdateSurvey extends RequestModel
{
    public null|array $config = null;

    public function __construct(public readonly SurveyId $id)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class]
        ];
    }
}
