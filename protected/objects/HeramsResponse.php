<?php
declare(strict_types = 1);

namespace prime\objects;


use SamIT\LimeSurvey\Interfaces\ResponseInterface;

/**
 * Class HeramsResponse
 * @package prime\objects
 */
class HeramsResponse
{
    /** @var array */
    private $data;
    /** @var HeramsCodeMap */
    private $map;

    public function __construct(
        ResponseInterface $response,
        HeramsCodeMap $map
    ) {
        $this->data = $response->getData();
        $this->map = $map;

        // Validate.
        if (!isset($this->data[$this->map->getSubjectId()])) {
            throw new \InvalidArgumentException('Invalid response');
        }
    }

    public function getLatitude(): ?float
    {
        return ((float) $this->data[$this->map->getLatitude()]) ?? null;
    }

    public function getLongitude(): ?float
    {
        return ((float) $this->data[$this->map->getLongitude()]) ?? null;
    }

    public function getType(): ?string
    {
        return $this->data[$this->map->getType()] ?? null;
    }

    public function getDate(): ?\DateTimeInterface
    {
        if (null !== $date = $this->data[$this->map->getDate()] ?? null) {
            $result = \DateTime::createFromFormat('Y-m-d', explode(' ', $date, 2)[0]);
            if (!$result instanceof \DateTimeInterface) {
                throw new \RuntimeException('Invalid date format: ' . $date);
            }
            return $result;
        }
        return null;
    }

    public function getName(): ?string
    {
        return $this->data[$this->map->getName()] ?? null;
    }

    public function getValueForCode(string $code)
    {
        if (array_key_exists($code, $this->data)) {
            return $this->data[$code];
        }
        // Try iteration.
        $result = [];
        foreach($this->data as $key => $value) {
            if (strpos($key . '[', $code) === 0
                && !empty($value)
            ) {
                // Prefix match.
                $result[] = $value;
            }
        }
        return !empty($result) ? $result : null;
    }

    public function getSubjectId(): string
    {
        return $this->data[$this->map->getSubjectId()];
    }

    public function getLocation(): ?string
    {
        return $this->data[$this->map->getLocation()] ?? null;
    }

    public function getServices(): array
    {
        $full = 0;
        $total = 0;
        foreach($this->data as $key => $value) {
            if (preg_match('/^QHeRAMS\d+$/', $key)) {
                if ($value === 'A1') {
                    $full++;
                }
                if (in_array($value, ['A1', 'A2', 'A3'])) {
                    $total++;
                }
            }
        }
        return $total === 0 ? null : 1.0 * $full / $total;
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
        echo '<pre>';
        var_dump($reasons);
        $percentage = 1.0 * array_shift($reasons) / count($services);
        var_dump($mainReason, $services);

        var_dump($percentage); die();
    }


}