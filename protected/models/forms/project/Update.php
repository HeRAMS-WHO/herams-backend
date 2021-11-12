<?php

declare(strict_types=1);

namespace prime\models\forms\project;

use prime\behaviors\LocalizableWriteBehavior;
use prime\models\ar\Project;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectType;
use prime\objects\enums\ProjectVisibility;
use prime\objects\LanguageSet;
use prime\traits\StrictModelScenario;
use prime\validators\ClientJsonValidator;
use prime\validators\CountryValidator;
use prime\values\ProjectId;
use prime\values\SurveyId;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class Update extends Model
{
    use StrictModelScenario;

    public null|string $country;
    public null|array $i18n;
    public LanguageSet $languages;
    public null|float $latitude;
    public null|float $longitude;
    public bool $manage_implies_create_hf;
    public null|array $overrides;
    public ProjectStatus $status;
    public string $title;
    public null|array $typemap;
    public ProjectVisibility $visibility;

    public function __construct(public ProjectId $id)
    {
        parent::__construct([]);
        $this->languages = LanguageSet::from([]);
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
        return Project::labels();
    }

    public function formName(): string
    {
        return 'Project';
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['title'], UniqueValidator::class, 'targetAttribute' => 'title', 'targetClass' => Project::class, 'filter' => ['not', ['id' => $this->id]]],
            [['latitude'], NumberValidator::class, 'integerOnly' => false, 'min' => -90, 'max' => 90],
            [['longitude'], NumberValidator::class, 'integerOnly' => false, 'min' => -180, 'max' => 180],

            // These are strongly typed so no validation is needed
            [['manage_implies_create_hf'], SafeValidator::class],
            ProjectVisibility::validatorFor('visibility'),
            ProjectStatus::validatorFor('status'),
            LanguageSet::validatorFor('languages'),
            [['typemap', 'overrides'], ClientJsonValidator::class],
            [['country'], CountryValidator::class]
        ];
    }

    public function scenarios(): array
    {
        $result = parent::scenarios();
        $result[ProjectType::surveyJs()->value] = [...$result[self::SCENARIO_DEFAULT], '!typemap'];
        $result[ProjectType::limesurvey()->value] = [...$result[self::SCENARIO_DEFAULT]];
        return $result;
    }
}
