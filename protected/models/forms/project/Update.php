<?php
declare(strict_types=1);

namespace prime\models\forms\project;

use prime\behaviors\LocalizableWriteBehavior;
use prime\models\ar\Project;
use prime\objects\enums\ProjectVisibility;
use prime\objects\LanguageSet;
use prime\validators\ClientJsonValidator;
use prime\validators\CountryValidator;
use prime\values\ProjectId;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class Update extends Model
{
    public string $title;
    public ProjectVisibility $visibility;
    public int $status;

    public null|array $i18n;

    public null|float $latitude;
    public null|float $longitude;

    public null|string $country;
    public bool $manage_implies_create_hf;

    public null|array $typemap;
    public null|array $overrides;

    public LanguageSet $languages;

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
            [['status', 'visibility', 'manage_implies_create_hf', 'languages'], SafeValidator::class],
            [['typemap', 'overrides'], ClientJsonValidator::class],
            [['country'], CountryValidator::class]
        ];
    }
}
