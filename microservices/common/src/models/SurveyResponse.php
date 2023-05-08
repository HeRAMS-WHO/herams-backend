<?php

declare(strict_types=1);

namespace herams\common\models;

use Carbon\Carbon;
use DateTimeInterface;
use herams\common\attributes\TriggersJob;
use herams\common\domain\facility\Facility;
use herams\common\domain\survey\Survey;
use herams\common\domain\user\User;
use herams\common\interfaces\HeramsResponseInterface;
use herams\common\interfaces\RecordInterface;
use herams\common\jobs\UpdateFacilityDataJob;
use herams\common\queries\ActiveQuery;
use herams\common\queries\SurveyResponseQuery;
use herams\common\validators\ExistValidator;
use herams\common\values\SurveyId;
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
final class SurveyResponse extends ActiveRecord implements HeramsResponseInterface, RecordInterface
{
    public function behaviors(): array
    {
        return [
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' =>  'latest_update_by',
            ],
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => 'latest_udpate_date',
                'value' => fn() => Carbon::now()
            ],
        ];
    }

    public static function find(): SurveyResponseQuery
    {
        return new SurveyResponseQuery(static::class);
    }

    public static function labels(): array
    {
        return [
            ...parent::labels(),
            'data' => \Yii::t('app', 'Data'),
            'facility_id' => \Yii::t('app', 'Facility'),
            'survey_id' => \Yii::t('app', 'Survey'),
        ];
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by',
        ]);
    }

    public function getUpdatedUser(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'latest_update_by',
        ]);
    }

    public function rules(): array
    {
        return [
            [['data', 'facility_id', 'survey_id'], RequiredValidator::class],
            [['data', 'survey_date', 'response_type', 'status', 'latest_udpate_date', 'latest_update_by'], SafeValidator::class],
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
