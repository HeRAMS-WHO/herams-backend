<?php

declare(strict_types=1);

namespace prime\models\forms\facility;

use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\objects\LanguageSet;
use prime\traits\DisableYiiLoad;
use prime\values\WorkspaceId;
use yii\base\Model;
use yii\validators\RequiredValidator;

class CreateForm extends Model
{
    use DisableYiiLoad;

    public array $data = [];

    public function __construct(
        private LanguageSet $languages,
        private SurveyForSurveyJsInterface $survey,
        private WorkspaceId $workspaceId,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function getLanguages(): LanguageSet
    {
        return $this->languages;
    }

    public function getSurvey(): SurveyForSurveyJsInterface
    {
        return $this->survey;
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }

    public function rules(): array
    {
        return [
            [['data'], RequiredValidator::class],
        ];
    }
}
