<?php

declare(strict_types=1);

namespace prime\models\facility;

use Collecthor\DataInterfaces\RecordInterface;
use prime\interfaces\CanCurrentUser;
use prime\interfaces\facility\FacilityForListInterface;
use prime\values\FacilityId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class FacilityForList implements FacilityForListInterface, RecordInterface
{
    public function __construct(
        private FacilityId $id,
        private string $name,
        private int $responseCount,
        private RecordInterface $record,
        private CanCurrentUser|null $checker = null
    ) {
    }

    public function canCurrentUser(string $permission): bool
    {
        return isset($this->checker) && $this->checker->canCurrentUser($permission);
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }

    public function getDataValue(array $path): string|int|float|bool|null|array
    {
        return $this->record->getDataValue($path);
    }

    public function allData(): array
    {
        return $this->record->allData();
    }
}
