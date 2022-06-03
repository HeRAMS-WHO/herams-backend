<?php

declare(strict_types=1);

namespace prime\models\ar;

use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\SurveyjsParser\ArrayRecord;
use prime\components\ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\models\ActiveRecord;
use prime\queries\FacilityQuery;
use prime\queries\ResponseForLimesurveyQuery;
use Ramsey\Uuid\Uuid;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\behaviors\TimestampBehavior;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Attributes
 * @property array $admin_data
 * @property string $alternative_name
 * @property bool $can_receive_situation_update
 * @property string $code
 * @property array $data
 * @property string|null $deactivated_at
 * @property string|null $deleted_at
 * @property array $i18n
 * @property int $id
 * @property float $latitude
 * @property float $longitude
 * @property string $name
 * @property bool $use_in_dashboarding
 * @property bool $use_in_list
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
 * @property-read ResponseForLimesurvey[] $responses
 * @property-read SurveyResponse[] $surveyResponses
 * @property-read Workspace $workspace
 */
class Facility extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            'virtualFields' => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => self::virtualFields(),
            ],
        ];
    }

    public function canReceiveSituationUpdate(): bool
    {
        return (bool) $this->can_receive_situation_update;
    }

    public static function find(): FacilityQuery
    {
        return new FacilityQuery(static::class);
    }

    public function getAdminSurvey(): ActiveQuery
    {
        return $this->hasOne(Survey::class, [
            'id' => 'admin_survey_id',
        ])
            ->via('project');
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

    public function getDataSurveyResponses(): ActiveQuery
    {
        return $this->getSurveyResponses()->andWhere([
            'survey_id' => $this->project->data_survey_id,
        ]);
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, [
            'id' => 'project_id',
        ])
            ->via('workspace');
    }

    public function getResponses(): ResponseForLimesurveyQuery
    {
        return $this->hasMany(ResponseForLimesurvey::class, [
            'facility_id' => 'id',
        ])->inverseOf('facility');
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
                'code' => \Yii::t('app', 'Code'),
                'data' => \Yii::t('app', 'Data'),
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

    public function getDataRecord(): RecordInterface
    {
        $dt = new \DateTime();
        return new ArrayRecord($this->data ?? [], $this->id, $dt, $dt);
    }

    public function getAdminRecord(): RecordInterface
    {
        $dt = new \DateTime();
        return new ArrayRecord($this->admin_data ?? [], $this->id, $dt, $dt);
    }

    public function rules(): array
    {
        return [
            [['name', 'workspace_id'], RequiredValidator::class],
            [['alternative_name', 'name', 'code'], StringValidator::class],
            [['workspace_id'],
                ExistValidator::class,
                'targetClass' => Workspace::class,
                'targetAttribute' => 'id',
            ],
            [['latitude', 'longitude'], NumberValidator::class],
        ];
    }

    public static function virtualFields(): array
    {
        return [
            'adminSurveyResponseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                //                VirtualFieldBehavior::GREEDY =>,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return $model->getAdminSurveyResponses()->count();
                },
            ],
            'dataSurveyResponseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                //                VirtualFieldBehavior::GREEDY =>,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return $model->getDataSurveyResponses()->count();
                },
            ],
        ];
    }
}
