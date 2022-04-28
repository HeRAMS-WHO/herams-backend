<?php
declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\RecordInterface;
use DateTimeInterface;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\values\FacilityId;

class CombinedHeramsFacilityRecord implements HeramsFacilityRecordInterface
{
    public function __construct(
        private RecordInterface $adminRecord,
        private RecordInterface $dataRecord,
        private FacilityId $facilityId
    ) {

    }


    public function getLatitude(): ?float
    {
        return mt_rand() / 10000;
    }

    public function getLongitude(): ?float
    {
        return mt_rand() / 10000;
    }

    public function getDataValue(array $path): string|int|float|null|array
    {
        return $this->adminRecord->getDataValue($path) ?? $this->dataRecord->getDataValue($path);
    }

    public function getRecordId(): int
    {
        return (int) $this->facilityId->getValue();
    }

    public function getStarted(): DateTimeInterface
    {
        return min($this->adminRecord->getStarted(), $this->dataRecord->getStarted());
    }

    public function getLastUpdate(): DateTimeInterface
    {
        return max($this->adminRecord->getLastUpdate(), $this->dataRecord->getLastUpdate());
    }

    public function asArray(): array
    {
        return [
            ...$this->adminRecord->asArray(),
            ...$this->dataRecord->asArray()
        ];
    }
}
