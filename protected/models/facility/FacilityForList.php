<?php
declare(strict_types=1);

namespace prime\models\facility;

use prime\values\FacilityId;
use prime\values\Point;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class FacilityForList implements \prime\interfaces\FacilityForList
{

    public function __construct(
        private FacilityId $id,
        private string $name,
        private null|string $alternativeName,
        private null|string $code,
        private null|Point $coordinates,
        private UuidInterface $uuid,
        private int $responseCount
    ) {
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlternativeName(): null|string
    {
        return $this->alternativeName;
    }

    public function getCode(): null|string
    {
        return $this->code;
    }

    public function getCoordinates(): null|Point
    {
        return $this->coordinates;
    }
}
