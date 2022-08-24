<?php
declare(strict_types=1);

namespace prime\helpers;

use Collecthor\SurveyjsParser\ArrayDataRecord;
use JetBrains\PhpStorm\Internal\TentativeType;

class NormalizedArrayDataRecord extends ArrayDataRecord implements \JsonSerializable
{
    public function __construct(array $data)
    {
        parent::__construct($this->prepare($data));
    }


    /**
     * Cleans up a data array for efficient storage and comparison.
     * @param array<string, mixed> $data
     * @return array
     */
    private function prepare(array $data): array
    {
        $result = array_filter($data, static fn(mixed $element) => !empty($element));
        ksort($result);
        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->allData();
    }
}
