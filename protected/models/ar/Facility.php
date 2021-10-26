<?php

declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\models\ActiveRecord;
use prime\queries\FacilityQuery;
use prime\queries\ResponseForLimesurveyQuery;
use Ramsey\Uuid\Uuid;
use yii\behaviors\TimestampBehavior;
use yii\validators\ExistValidator;

/**
 * Attributes
 * @property string $alternative_name
 * @property string $code
 * @property string|null $created_at
 * @property string|null $deactivated_at
 * @property string|null $deleted_at
 * @property array $i18n
 * @property int $id
 * @property string $name
 * @property string|null $updated_at
 * @property string $uuid
 * @property int $workspace_id
 *
 * Relations
 * @property-read ResponseForLimesurvey[] $responses
 * @property-read Workspace $workspace
 */
class Facility extends ActiveRecord
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        // TODO: Move to behavior
        if (!isset($this->uuid)) {
            $this->uuid = Uuid::uuid6()->getBytes();
        }
    }

    public static function find(): FacilityQuery
    {
        return new FacilityQuery(static::class);
    }

    public static function labels(): array
    {
        return ArrayHelper::merge(
            parent::labels(),
            [
                'alternative_name' => \Yii::t('app', 'Alternative name'),
                'code' => \Yii::t('app', 'Code'),
                'coordinates' => \Yii::t('app', 'Coordinates'),
                'deactivated_at' => \Yii::t('app', 'Deactivated at'),
                'i18n' => \Yii::t('app', 'Localization'),
                'id' => \Yii::t('app', 'Facility ID'),
                'name' => \Yii::t('app', 'Name'),
                'workspace_id' => \Yii::t('app', 'Workspace'),
                'uuid' => \Yii::t('app', 'UUID'),
            ]
        );
    }

    public function getResponses(): ResponseForLimesurveyQuery
    {
        return $this->hasMany(ResponseForLimesurvey::class, [
            'facility_id' => 'id',
        ])->inverseOf('facility');
    }

    public function getWorkspace(): ActiveQuery
    {
        return $this->hasOne(WorkspaceForLimesurvey::class, [
            'id' => 'workspace_id'
        ]);
    }

    public function rules(): array
    {
        return [
            [['workspace_id'], ExistValidator::class, 'targetClass' => Workspace::class, 'targetAttribute' => 'id']
        ];
    }
}
