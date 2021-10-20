<?php
declare(strict_types=1);

namespace prime\models\ar;

use Carbon\Carbon;
use prime\components\ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsSubject;
use prime\queries\ResponseForLimesurveyQuery;
use yii\behaviors\TimestampBehavior;
use yii\validators\RequiredValidator;

/**
 * Attributes
 * @property int $auto_increment_id
 * @property string|null $created_at
 * @property array $data
 * @property string|\DateTimeInterface $date The date of the information
 * @property int $facility_id
 * @property string $hf_id
 * @property int $id
 * @property int $survey_id
 * @property string|null $updated_at
 * @property int $workspace_id
 *
 * Virtual attributes
 * @property Facility $facility
 * @property-read Workspace $workspace
 *
 * Relations
 * @property Project $project
 * @property-read HeramsResponseInterface[] $responses
 */
class ResponseForLimesurvey extends ActiveRecord implements HeramsResponseInterface
{
    private static $surveySubjectKeys = [];

    public function afterFind()
    {
        parent::afterFind();
        $data = $this->data ?? [];
        ksort($data);
        $this->data = $data;
        $this->setOldAttribute('data', $data);
    }

    public function afterRefresh()
    {
        parent::afterRefresh();
        $data = $this->data;
        ksort($data);
        $this->data = $data;
        $this->setOldAttribute('data', $data);
    }

    public static function find(): ResponseForLimesurveyQuery
    {
        return \Yii::createObject(ResponseForLimesurveyQuery::class, [get_called_class()]);
    }

    public function getAccessibility(): string
    {
        return $this->data[$this->getMap()->getFunctionality()] ?? HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getAutoIncrementId(): int
    {
        return $this->auto_increment_id;
    }

    public function getCondition(): string
    {
        return $this->data[$this->getMap()->getFunctionality()] ?? HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getDate(): ?Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->date);
    }

    public function getFacility(): ActiveQuery
    {
        return $this->hasOne(Facility::class, ['id' => 'facility_id']);
    }

    public function getFunctionality(): string
    {
        return $this->data[$this->getMap()->getFunctionality()] ?? HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getLatitude(): ?float
    {
        if (!isset($this->data['MoSDGPS']['SQ001']) || empty($this->data['MoSDGPS']['SQ001'])) {
            return null;
        }
        return (float) $this->data['MoSDGPS']['SQ001'];
    }

    public function getLimesurveyUrl(string $language): string|null
    {
        if (!isset($this->survey_id)) {
            return null;
        }
        return "https://ls.herams.org/{$this->survey_id}?ResponsePicker={$this->id}&token={$this->workspace->token}&lang={$language}&newtest=Y";
    }

    public function getLocation(): ?string
    {
        return $this->data[$this->getMap()->getLocation()] ?? null;
    }

    public function getLongitude(): ?float
    {
        if (!isset($this->data['MoSDGPS']['SQ002']) || empty($this->data['MoSDGPS']['SQ002'])) {
            return null;
        }
        return (float) $this->data['MoSDGPS']['SQ002'];
    }

    public function getMainReason(): ?string
    {
        $reasons = [];
        $services = [];
        foreach ($this->data as $key => $value) {
            if (preg_match('/^(QHeRAMS\d+)x\[\d+\]$/', $key, $matches)) {
                if (empty($value)) {
                    continue;
                }
                $services[$matches[1]] = true;
                $reasons[$value] = ($reasons[$value] ?? 0) + 1;
            }
        }

        arsort($reasons);
        if (empty($reasons)) {
            return null;
        }
        $mainReason = array_keys($reasons)[0];
        return $mainReason;
    }

    public function getMap(): HeramsCodeMap
    {
        return new HeramsCodeMap();
    }

    public function getName(): ?string
    {
        return $this->data[$this->getMap()->getName()] ?? null;
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'project_id'])->via('workspace');
    }

    public function getRawData(): array
    {
        return $this->data;
    }

    public function getSubjectAvailability(): float
    {
        $score = 0;
        $limit = 0;
        foreach ($this->getSubjects() as $heramsSubject) {
            switch ($heramsSubject->getAvailability()) {
                case HeramsSubject::FULLY_AVAILABLE:
                    $score += 3;
                    $limit += 1;
                    break;
                case HeramsSubject::PARTIALLY_AVAILABLE:
                    $score += 1;
                    $limit += 1;
                    break;
                case HeramsSubject::NOT_PROVIDED:
                    break;
                case HeramsSubject::NOT_AVAILABLE:
                default:
                    // Unknown values count as not available
                    $score += 0;
                    $limit += 1;
                    break;
            }
        }

        return $limit > 0 ? 100.0 * $score / (3 * $limit) : 0;
    }

    public function getSubjectAvailabilityBucket(): int
    {
        switch (intdiv($this->getSubjectAvailability(), 25)) {
            case 0:
                return HeramsResponseInterface::BUCKET25;
            case 1:
                return HeramsResponseInterface::BUCKET2550;
            case 2:
                return HeramsResponseInterface::BUCKET5075;
            default:
                return HeramsResponseInterface::BUCKET75100;
        }
    }

    public function getSubjectId(): string
    {
        return $this->hf_id;
    }

    private function getSubjectKeys()
    {
        if (!isset(self::$surveySubjectKeys[$this->survey_id])) {
            self::$surveySubjectKeys[$this->survey_id] = [];
        }
        foreach ($this->data as $key => $dummy) {
            if (isset(self::$surveySubjectKeys[$this->survey_id][$key])) {
                continue;
            }

            if (preg_match($this->getMap()->getSubjectExpression(), $key)) {
                self::$surveySubjectKeys[$this->survey_id][$key] = true;
            }
        }

        return array_keys(self::$surveySubjectKeys[$this->survey_id]);
    }

    /**
     * @return iterable|HeramsSubject[]
     */
    public function getSubjects(): iterable
    {
        foreach ($this->getSubjectKeys() as $key) {
            yield new HeramsSubject($this, $key);
        }
    }

    public function getType(): ?string
    {
        return $this->data[$this->getMap()->getType()] ?? null;
    }

    public function getValueForCode(string $code)
    {
        return $this->data[$code] ?? null;
    }

    public function getWorkspace(): ActiveQuery
    {
        return $this->hasOne(Workspace::class, ['id' => 'workspace_id']);
    }

    public function rules(): array
    {
        return [
            [['date', 'hf_id', 'id', 'survey_id', 'workspace_id'], RequiredValidator::class]
        ];
    }

    public static function tableName(): string
    {
        return '{{%response_for_limesurvey}}';
    }
}
