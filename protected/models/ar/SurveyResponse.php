<?php

declare(strict_types=1);

namespace prime\models\ar;

use Carbon\Carbon;
use Collecthor\DataInterfaces\RecordInterface;
use DateTimeInterface;
use prime\components\ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\validators\ExistValidator;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

use function GuzzleHttp\Promise\is_settled;

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
        // TODO: Implement getAccessibility() method.
        return HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getAutoIncrementId(): int
    {
        return $this->id;
    }

    public function getCondition(): string
    {
        // TODO: Implement getCondition() method.
        return HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getDate(): ?Carbon
    {
        return new Carbon($this->created_at);
    }

    public function getFunctionality(): string
    {
        // TODO: Implement getFunctionality() method.
        return HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        // TODO: Implement getLatitude() method.
        return null;
    }

    public function getLocation(): ?string
    {
        // TODO: Implement getLocation() method.
        return null;
    }

    public function getLongitude(): ?float
    {
        // TODO: Implement getLongitude() method.
        return null;
    }

    public function getMainReason(): ?string
    {
        // TODO: Implement getMainReason() method.
        return null;
    }

    public function getName(): ?string
    {
        // TODO: Implement getName() method.
        return null;
    }

    public function getRawData(): array
    {
        return $this->data;
    }

    public function getSubjectAvailability(): float
    {
        // TODO: Implement getSubjectAvailability() method.
        return 0;
    }

    public function getSubjectAvailabilityBucket(): int
    {
        // TODO: Implement getSubjectAvailabilityBucket() method.
        return 0;
    }

    public function getSubjectId(): string
    {
        return (string) $this->facility_id;
    }

    public function getSubjects(): iterable
    {
        // TODO: Implement getSubjects() method.
        return [];
    }

    public function getType(): ?string
    {
        // TODO: Implement getType() method.
        return null;
    }

    public function getValueForCode(string $code)
    {
        // TODO: Implement getValueForCode() method.
        return null;
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
            [['data'], SafeValidator::class],
            [['facility_id'], ExistValidator::class, 'targetRelation' => 'facility'],
            [['survey_id'], ExistValidator::class, 'targetRelation' => 'survey'],
        ];
    }

    public function getDataValue(array $path): string|int|float|null|array
    {
        $data = $this->data;
        while (!empty($path)) {
            $key = array_shift($path);
            if (!isset($data[$key])) {
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
            'data' => $this->data
        ];
    }
}
