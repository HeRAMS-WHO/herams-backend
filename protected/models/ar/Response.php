<?php


namespace prime\models\ar;

use Carbon\Carbon;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsSubject;
use prime\queries\ResponseQuery;
use yii\validators\RequiredValidator;

/**
 * Class Response
 * @package prime\models\ar
 * @property string|\DateTimeInterface $last_updated The last time this response was synced
 * @property int $workspace_id
 * @property int $id
 * @property string|\DateTimeInterface $date The date of the information
 * @property int $survey_id
 * @property array $data
 * @property string $hf_id
 * @property Workspace $workspace
 * @property Project $project
 */
class Response extends ActiveRecord implements HeramsResponseInterface
{
    private static $surveySubjectKeys = [];

    public function getMap(): HeramsCodeMap
    {
        return new HeramsCodeMap();
    }

    public function beforeSave($insert)
    {
        $this->last_updated = Carbon::now();
        return parent::beforeSave($insert);
    }

    public static function find(): ResponseQuery
    {
        return new ResponseQuery(self::class);
    }

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

    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['id' => 'workspace_id'])->inverseOf('responses');
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->via('workspace');
    }

    public function getLatitude(): ?float
    {
        return $this->data['MoSDGPS']['SQ001'] ?? null;
    }

    public function getLongitude(): ?float
    {
        return $this->data['MoSDGPS']['SQ002'] ?? null;
    }

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getType(): ?string
    {
        return $this->data[$this->getMap()->getType()] ?? null;
    }

    public function getName(): ?string
    {
        return $this->data[$this->getMap()->getName()] ?? null;
    }

    public function getValueForCode(string $code)
    {
        return $this->data[$code] ?? null;
    }

    public function getSubjectId(): string
    {
        return $this->hf_id;
    }

    public function getLocation(): ?string
    {
        return $this->data[$this->getMap()->getLocation()] ?? null;
    }

    public function getDate(): ?Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->date);
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

    public function getFunctionality(): string
    {
        return $this->data[$this->getMap()->getFunctionality()] ?? HeramsResponseInterface::UNKNOWN_VALUE;
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

    public function rules()
    {
        return [
            [['date', 'hf_id', 'id', 'survey_id', 'workspace_id'], RequiredValidator::class]
        ];
    }


    public function getRawData(): array
    {
        return $this->data;
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
}
