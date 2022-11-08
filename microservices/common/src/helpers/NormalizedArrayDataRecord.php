<?php

declare(strict_types=1);

namespace herams\common\helpers;

use Collecthor\SurveyjsParser\ArrayDataRecord;

class NormalizedArrayDataRecord extends ArrayDataRecord implements \JsonSerializable
{
    public function __construct(array $data)
    {
        parent::__construct($this->prepare($data));
    }

    /**
     * Cleans up a data array for efficient storage and comparison.
     * @param array<string, mixed> $data
     */
    private function prepare(array $data): array
    {
        $result = array_filter($data, static fn (mixed $element) => ! empty($element));
        ksort($result);
        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->allData();
    }
}
