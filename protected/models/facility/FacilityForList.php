<?php

declare(strict_types=1);

namespace prime\models\facility;

use prime\interfaces\CanCurrentUser;
use prime\interfaces\facility\FacilityForListInterface;
use prime\models\ar\Facility;
use prime\objects\enums\FacilityTier;
use prime\values\FacilityId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class FacilityForList implements FacilityForListInterface
{
    public function __construct(
        private FacilityId $id,
        private string $name,
        private null|string $alternativeName,
        private null|string $code,
        private null|float $latitude,
        private null|float $longitude,
        private int $responseCount,
        private FacilityTier $tier,
        private CanCurrentUser|null $checker = null
    ) {
    }

    public function canCurrentUser(string $permission): bool
    {
        return isset($this->checker) && $this->checker->canCurrentUser($permission);
    }

    public function getAlternativeName(): null|string
    {
        return $this->alternativeName;
    }

    public function getCode(): null|string
    {
        return $this->code;
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function getLatitude(): null|float
    {
        return $this->latitude;
    }

    public function getLongitude(): null|float
    {
        return $this->longitude;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }

    public function getTier(): FacilityTier
    {
        return $this->tier;
    }
}
