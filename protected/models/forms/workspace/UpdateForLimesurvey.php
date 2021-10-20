<?php

declare(strict_types=1);

namespace prime\models\forms\workspace;

use prime\behaviors\LocalizableWriteBehavior;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectVisibility;
use prime\objects\LanguageSet;
use prime\validators\ClientJsonValidator;
use prime\validators\CountryValidator;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\WorkspaceId;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class UpdateForLimesurvey extends Model
{
    public null|array $i18n;
    public string $title;
    public string $token;

    public function __construct(
        private WorkspaceId $id,
        array $config = [],
    ) {
        parent::__construct($config);
    }

    public function behaviors(): array
    {
        return [
            LocalizableWriteBehavior::class => [
                'class' => LocalizableWriteBehavior::class,
                'attributes' => ['title']
            ]
        ];
    }

    public function attributeLabels(): array
    {
        return Workspace::labels();
    }

    public function formName(): string
    {
        return 'Workspace';
    }

    public function getId(): WorkspaceId
    {
        return $this->id;
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
        ];
    }
}
