<?php

declare(strict_types=1);

namespace prime\modules\Api\models;

use prime\helpers\LocalizedString;
use prime\models\ar\Project;
use prime\models\ar\Survey;
use prime\models\RequestModel;
use prime\objects\enums\ProjectVisibility;
use prime\validators\BackedEnumValidator;
use prime\validators\ExistValidator;
use prime\values\SurveyId;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

final class NewProject extends RequestModel
{
    public null|LocalizedString $title = null;

    public null|SurveyId $dataSurveyId = null;

    public null|SurveyId $adminSurveyId = null;

    public null|ProjectVisibility $visibility = null;

    public function __construct()
    {
        parent::__construct();
        $this->visibility = ProjectVisibility::Public;
    }

    public function attributeLabels(): array
    {
        return Project::labels();
    }

    public function rules(): array
    {
        return [
            [['title', 'dataSurveyId', 'adminSurveyId'], RequiredValidator::class],
            [['title'],
                UniqueValidator::class,
                'targetClass' => Project::class,
                'targetAttribute' => 'title',
            ],
            [['dataSurveyId', 'adminSurveyId'],
                ExistValidator::class,
                'targetAttribute' => 'id',
                'targetClass' => Survey::class,
            ],
            [['visibility'],
                BackedEnumValidator::class,
                'example' => ProjectVisibility::Public,
            ],
        ];
    }
}
