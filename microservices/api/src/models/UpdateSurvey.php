<?php

declare(strict_types=1);

namespace herams\api\models;

use herams\common\helpers\surveyjs\RequiredVariableValidator;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\models\RequestModel;
use herams\common\values\SurveyId;
use yii\validators\RequiredValidator;

final class UpdateSurvey extends RequestModel
{
    public null|array $config = null;

    public function __construct(public readonly SurveyId $id, private readonly SurveyParser $surveyParser)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class],
            [['config'], RequiredVariableValidator::class, 'surveyParser' => $this->surveyParser, 'requiredVariables' => ['name']]

        ];
    }
}
