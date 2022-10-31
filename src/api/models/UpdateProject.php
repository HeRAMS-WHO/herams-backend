<?php

declare(strict_types=1);

namespace herams\api\models;

use prime\helpers\LocalizedString;
use prime\models\ar\Project;
use prime\models\RequestModel;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectVisibility;
use prime\validators\BackedEnumValidator;
use prime\validators\CountryValidator;
use prime\values\Latitude;
use prime\values\Longitude;
use prime\values\ProjectId;
use prime\values\SurveyId;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

final class UpdateProject extends RequestModel
{
    public null|LocalizedString $title = null;

    public null|ProjectVisibility $visibility = null;

    public null|ProjectStatus $status = null;

    public null|string $country = null;

    public bool $manageImpliesCreateHf = false;

    public null|SurveyId $dataSurveyId = null;

    public null|SurveyId $adminSurveyId = null;

    public null|Latitude $latitude = null;

    public null|Longitude $longitude = null;

    public array $languages = [];

    public function __construct(public readonly ProjectId $id)
    {
        parent::__construct();
    }

    public function attributeLabels(): array
    {
        return Project::labels();
    }

    public function rules(): array
    {
        return [
            [['title', 'country', 'visibility', 'adminSurveyId', 'dataSurveyId'], RequiredValidator::class],
            [['country'], CountryValidator::class],
            [['languages'], SafeValidator::class],
            [['latitude', 'longitude', 'manageImpliesCreateHf'], SafeValidator::class],
            [['visibility'],
                BackedEnumValidator::class,
                'example' => ProjectVisibility::Public,
            ],
        ];
    }
}
