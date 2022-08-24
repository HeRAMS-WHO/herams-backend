<?php

declare(strict_types=1);

namespace prime\models\ar;

use Carbon\Carbon;
use DateTimeInterface;
use prime\attributes\TriggersJob;
use prime\components\ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\RecordInterface;
use prime\jobs\UpdateFacilityDataJob;
use prime\models\ActiveRecord;
use prime\validators\ExistValidator;
use prime\values\SurveyId;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

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
#[TriggersJob(UpdateFacilityDataJob::class, 'facility_id')]
class SurveyResponse extends ActiveRecord implements HeramsResponseInterface, RecordInterface
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

    public static function labels(): array
    {
        return ArrayHelper::merge(
            parent::labels(),
            [
                'data' => \Yii::t('app', 'Data'),
                'facility_id' => \Yii::t('app', 'Facility'),
                'survey_id' => \Yii::t('app', 'Survey'),
            ]
        );
    }

    public function getAccessibility(): string
    {
        return HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getAutoIncrementId(): int
    {
        return $this->id;
    }

    public function getCondition(): string
    {
        return HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getDate(): ?Carbon
    {
        return new Carbon($this->created_at);
    }

    public function getFunctionality(): string
    {
        return HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return null;
    }

    public function getLocation(): ?string
    {
        return null;
    }

    public function getLongitude(): ?float
    {
        return null;
    }

    public function getMainReason(): ?string
    {
        return null;
    }

    public function getName(): ?string
    {
        return null;
    }

    public function getRawData(): array
    {
        return $this->data;
    }

    public function getSubjectAvailability(): float
    {
        return 0;
    }

    public function getSubjectAvailabilityBucket(): int
    {
        return 0;
    }

    public function getSubjectId(): string
    {
        return (string) $this->facility_id;
    }

    public function getSubjects(): iterable
    {
        return [];
    }

    public function getType(): ?string
    {
        return null;
    }

    public function getValueForCode(string $code)
    {
        return null;
    }

    public function getFacility(): ActiveQuery
    {
        return $this->hasOne(Facility::class, [
            'id' => 'facility_id',
        ]);
    }

    public function getSurvey(): ActiveQuery
    {
        return $this->hasOne(Survey::class, [
            'id' => 'survey_id',
        ]);
    }

    public function rules(): array
    {
        return [
            [['data', 'facility_id', 'survey_id'], RequiredValidator::class],
            [['data'], SafeValidator::class],
            [['facility_id'],
                ExistValidator::class,
                'targetRelation' => 'facility',
            ],
            [['survey_id'],
                ExistValidator::class,
                'targetRelation' => 'survey',
            ],
        ];
    }

    public function getDataValue(array $path): string|int|float|null|array
    {
        $data = $this->data;
        while (! empty($path)) {
            $key = array_shift($path);
            if (! isset($data[$key])) {
                return null;
            }
            $data = $data[$key];
        }
        return $data;
    }

    public function getRecordId(): int
    {
        return $this->id;
    }

    public function getStarted(): DateTimeInterface
    {
        return new Carbon($this->created_at);
    }

    public function getLastUpdate(): DateTimeInterface
    {
        return new Carbon($this->created_at);
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'started' => $this->getStarted(),
            'lastUpdate' => $this->getLastUpdate(),
            'data' => $this->data,
        ];
    }

    public function getSurveyId(): SurveyId
    {
        return new SurveyId($this->survey_id);
    }

    public function allData(): array
    {
        return $this->data;
    }
}
