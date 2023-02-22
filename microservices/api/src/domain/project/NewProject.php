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
use herams\common\validators\CountryValidator;
use herams\common\validators\ExistValidator;
use herams\common\values\Latitude;
use herams\common\values\Longitude;
use herams\common\values\SurveyId;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\UrlValidator;

class NewProject extends RequestModel
{
    public null|LocalizedString $title = null;

    public null|SurveyId $dataSurveyId = null;

    public null|SurveyId $adminSurveyId = null;

    public null|ProjectVisibility $visibility = null;

    public null|Latitude $latitude = null;

    public null|Longitude $longitude = null;

    public array $languages = [];

    public string $country = '';

    public string $dashboardUrl = '';

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
            [['title', 'country', 'visibility', 'adminSurveyId', 'dataSurveyId', 'primaryLanguage', 'languages'], RequiredValidator::class],
            [['languages'], RangeValidator::class, 'range' => Locale::keys(), 'allowArray' => true],
            [['primaryLanguage'], function() {
                if (!in_array($this->primaryLanguage, $this->languages)) {
                    $this->addError('primaryLanguage', \Yii::t('app', 'The primary language must be selected in the list of active languages'));
                }
            }],
            [['latitude', 'longitude'], SafeValidator::class],
            [['dataSurveyId', 'adminSurveyId'],
                ExistValidator::class,
                'targetAttribute' => 'id',
                'targetClass' => Survey::class,
            ],
            [['visibility'],
                BackedEnumValidator::class,
                'example' => ProjectVisibility::Public,
            ],
            [['country'], CountryValidator::class],
            [['dashboardUrl'], UrlValidator::class, 'validSchemes' => ['https']]
        ];
    }
}
