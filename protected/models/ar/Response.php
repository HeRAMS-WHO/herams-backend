<?php


namespace prime\models\ar;


use Carbon\Carbon;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsSubject;
use function iter\filter;
use function iter\toArrayWithKeys;

class Response extends ActiveRecord implements HeramsResponseInterface
{
    private static $surveySubjectKeys = [];
    private static $surveyArrayKeys = [];

    public function getMap(): HeramsCodeMap
    {
        return new HeramsCodeMap();
    }
    public function beforeSave($insert)
    {
        $this->last_updated = Carbon::now();
        return parent::beforeSave($insert);
    }

    public function loadData(array $data)
    {
        $data = toArrayWithKeys(filter(function($value) {
            return !empty($value); //$value !== null;
        }, $data));

        $this->token = $data['token'];
        $this->id = $data['id'];
        // Remove some keys from the data.
        unset(
            $data['submitdate'],
            $data['startdate'],
            $data['datestamp'],
            $data['startlanguage'],
            $data['id'],
            $data['token'],
            $data['lastpage']
        );
        $this->data = $data;
    }

    public function getLatitude(): ?float
    {
        return $this->data[$this->getMap()->getLatitude()] ?? null;
    }

    public function getLongitude(): ?float
    {
        return $this->data[$this->getMap()->getLongitude()] ?? null;
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
        if (array_key_exists($code, $this->data)) {
            $result = $this->data[$code];
        } else {
            // Try iteration.
            $result = [];
            foreach($this->getKeysForCode($code) as $key) {
                if (isset($this->data[$key]) && !empty($this->data[$key])) {
                    $result[] = $this->data[$key];
                }
            }
        }
        return !empty($result) ? $result : null;
    }

    private function getKeysForCode(string $code)
    {
        if (!isset(self::$surveyArrayKeys[$this->survey_id][$code])) {
            self::$surveyArrayKeys[$this->survey_id][$code] = [];
            foreach ($this->data as $key => $dummy) {
                if (strpos($key . '[', $code) === 0) {
                    self::$surveyArrayKeys[$this->survey_id][$code][] = $key;
                }
            }
        }
        return self::$surveyArrayKeys[$this->survey_id][$code];
    }
    public function getSubjectId(): string
    {
        return $this->data[$this->getMap()->getSubjectId()] ?? null;
    }

    public function getLocation(): ?string
    {
        return $this->data[$this->getMap()->getLocation()] ?? null;
    }

    public function getDate(): ?Carbon
    {
        if (!isset($this->data[$this->getMap()->getDate()])) {
            return null;
        }
        return Carbon::createFromFormat('Y-m-d', explode(' ', $this->data[$this->getMap()->getDate()], 2)[0]);
    }

    private function getSubjectKeys()
    {
        if (!isset(self::$surveySubjectKeys[$this->surveyId])) {
            self::$surveySubjectKeys[$this->surveyId] = [];
            foreach($this->data as $key => $dummy) {
                if (preg_match($this->getMap()->getSubjectExpression(), $key)) {
                    self::$surveySubjectKeys[$this->surveyId][] = $key;
                }
            }
        }

        return self::$surveySubjectKeys[$this->surveyId];

    }

    /**
     * @return HeramsSubject[]
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

        return $limit > 0 ? 100.0 * $score / $limit : 0;
    }

    public function getFunctionality(): string
    {
        return $this->data[$this->getMap()->getFunctionality()] ?? HeramsResponseInterface::UNKNOWN_VALUE;
    }

    public function getMainReason(): ?string
    {
        $reasons = [];
        $services = [];
        foreach($this->data as $key => $value) {
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

    public function getRawData(): array
    {
        return $this->data;
    }
}