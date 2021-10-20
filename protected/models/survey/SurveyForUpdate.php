<?php

declare(strict_types=1);

namespace prime\models\survey;

use prime\traits\DisableYiiLoad;
use prime\values\SurveyId;
use yii\base\Model;
use yii\validators\RequiredValidator;

class SurveyForUpdate extends Model
{
    use DisableYiiLoad;

    public array $config;

    public function __construct(
        private SurveyId $surveyId,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'config' => \Yii::t('app', 'Config'),
        ];
    }

    public function getSurveyId(): SurveyId
    {
        return $this->surveyId;
    }

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class],
        ];
    }
}
