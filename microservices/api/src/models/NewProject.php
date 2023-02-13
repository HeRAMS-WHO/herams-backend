<?php

declare(strict_types=1);

namespace herams\api\models;

use herams\common\domain\survey\Survey;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\LocalizedString;
use herams\common\models\Project;
use herams\common\models\RequestModel;
use herams\common\validators\BackedEnumValidator;
use herams\common\validators\ExistValidator;
use herams\common\values\SurveyId;
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

    /**
     * @return array<string,string>
     */
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
