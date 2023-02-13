<?php

declare(strict_types=1);

namespace herams\api\models;

use herams\common\enums\ProjectStatus;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\LocalizedString;
use herams\common\models\Project;
use herams\common\models\RequestModel;
use herams\common\validators\BackedEnumValidator;
use herams\common\validators\CountryValidator;
use herams\common\values\Latitude;
use herams\common\values\Longitude;
use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use yii\validators\BooleanValidator;
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

    public string $primaryLanguage = '';

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
            [['title', 'country', 'visibility', 'adminSurveyId', 'dataSurveyId', 'primaryLanguage', 'languages'], RequiredValidator::class],
            [['country'], CountryValidator::class],
            [['languages'], SafeValidator::class],
            [['latitude', 'longitude'], SafeValidator::class],
            [['manageImpliesCreateHf'], BooleanValidator::class],
            [['visibility'],
                BackedEnumValidator::class,
                'example' => ProjectVisibility::Public,
            ],
        ];
    }
}
