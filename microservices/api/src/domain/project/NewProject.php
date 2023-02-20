<?php

declare(strict_types=1);

namespace herams\api\domain\project;

use herams\common\domain\survey\Survey;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\Locale;
use herams\common\helpers\LocalizedString;
use herams\common\models\Project;
use herams\common\models\RequestModel;
use herams\common\validators\BackedEnumValidator;
use herams\common\validators\ExistValidator;
use herams\common\values\SurveyId;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;

final class NewProject extends RequestModel
{
    public null|LocalizedString $title = null;

    public null|SurveyId $dataSurveyId = null;

    public null|SurveyId $adminSurveyId = null;

    public null|ProjectVisibility $visibility = null;

    public array $languages = [];

    public string $primaryLanguage = '';

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
            [['languages'], RangeValidator::class, 'range' => Locale::keys(), 'allowArray' => true],
            [['primaryLanguage'], function() {
                if (!in_array($this->primaryLanguage, $this->languages)) {
                    $this->addError('primaryLanguage', \Yii::t('app', 'The primary language must be selected in the list of active languages'));
                }
            }],
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
