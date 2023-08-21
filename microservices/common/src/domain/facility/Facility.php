<?php

declare(strict_types=1);

namespace herams\common\domain\facility;
use herams\common\domain\survey\Survey;
use herams\common\models\ActiveRecord;
use herams\common\models\Project;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\queries\ActiveQuery;
use herams\common\queries\FacilityQuery;
use prime\helpers\ArrayHelper;
use yii\db\Expression;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Attributes
 * @property array $admin_data
 * @property string $alternative_name
 * @property bool $can_receive_situation_update
 * @property array $situation_data
 * @property string|null $deactivated_at
 * @property string|null $deleted_at
 * @property array $i18n
 * @property int $id
 * @property float $latitude
 * @property float $longitude
 * @property string $name
 * @property bool $use_in_dashboarding
 * @property bool $use_in_list
 * @property string $tier
 * @property int $workspace_id
 *
 * Virtual fields
 * @property-read int $adminSurveyResponseCount
 * @property-read int $dataSurveyResponseCount
 *
 * Relations
 * @property-read Survey $adminSurvey
 * @property-read Survey $dataSurvey
 * @property-read Project $project
 * @property-read SurveyResponse[] $surveyResponses
 * @property-read Workspace $workspace
 */
class Facility extends ActiveRecord
{

    public static function find(): FacilityQuery
    {
        return new FacilityQuery(static::class);
    }

    public function getAdminSurveyResponses(): ActiveQuery
    {
        return $this->getSurveyResponses()->andWhere([
            'survey_id' => $this->project->admin_survey_id,
        ]);
    }

    public function getDataSurvey(): ActiveQuery
    {
        return $this->hasOne(Survey::class, [
            'id' => 'data_survey_id',
        ])
            ->via('project');
    }
    public function getLatestSurveyResponse() {
        return $this->hasOne(SurveyResponse::class, ['facility_id' => 'id'])
            ->where(['!=', 'status', 'Deleted'])
            ->addOrderBy(['date_of_update' => SORT_DESC]);
    }
    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, [
            'id' => 'project_id',
        ])
            ->via('workspace');
    }

    public function getSurveyResponses(): ActiveQuery
    {
        return $this->hasMany(SurveyResponse::class, [
            'facility_id' => 'id',
        ]);
    }

    public function getWorkspace(): ActiveQuery
    {
        return $this->hasOne(Workspace::class, [
            'id' => 'workspace_id',
        ]);
    }
    public static function labels(): array
    {
        return ArrayHelper::merge(
            parent::labels(),
            [
                'admin_data' => \Yii::t('app', 'Admin data'),
                'alternative_name' => \Yii::t('app', 'Alternative name'),
                'can_receive_situation_update' => \Yii::t('app', 'Can receive situation update'),
                'situation_data' => \Yii::t('app', 'Data'),
                'deactivated_at' => \Yii::t('app', 'Deactivated at'),
                'i18n' => \Yii::t('app', 'Localization'),
                'id' => \Yii::t('app', 'Facility ID'),
                'latitude' => \Yii::t('app', 'Latitude'),
                'longitude' => \Yii::t('app', 'Longitude'),
                'name' => \Yii::t('app', 'Name'),
                'use_in_dashboarding' => \Yii::t('app', 'Use in dashboarding'),
                'use_in_list' => \Yii::t('app', 'Use in list'),
                'workspace_id' => \Yii::t('app', 'Workspace'),
            ]
        );
    }



    public function rules(): array
    {
        return [
            [['workspace_id'], RequiredValidator::class],
            [['status'], StringValidator::class],
            [['workspace_id'],
                ExistValidator::class,
                'targetClass' => Workspace::class,
                'targetAttribute' => 'id',
            ],
            [['latitude', 'longitude'], NumberValidator::class],
        ];
    }

}
