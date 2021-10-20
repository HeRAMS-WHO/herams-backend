<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\models\ActiveRecord;
use prime\validators\ExistValidator;
use prime\validators\JsonValidator;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\validators\RequiredValidator;

/**
 * Attributes
 * @property int $id
 * @property string $created_at
 * @property int $created_by
 * @property array $data
 * @property int $facility_id
 * @property int $survey_id
 *
 * Relations
 * @property-read Facility $facility
 * @property-read Survey $survey
 */
class SurveyResponse extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getFacility(): ActiveQuery
    {
        return $this->hasOne(Facility::class, ['id' => 'facility_id']);
    }

    public function getSurvey(): ActiveQuery
    {
        return $this->hasOne(Survey::class, ['id' => 'survey_id']);
    }

    public function rules(): array
    {
        return [
            [['data', 'facility_id', 'survey_id'], RequiredValidator::class],
            [['data'], JsonValidator::class],
            [['facility_id'], ExistValidator::class, 'targetRelation' => 'facility'],
            [['survey_id'], ExistValidator::class, 'targetRelation' => 'survey'],
        ];
    }
}
