<?php
declare(strict_types=1);

namespace prime\interfaces\facility;

use prime\values\FacilityId;
use prime\values\Point;

interface FacilityForListInterface
{
    public const ID = "id";
    public const UUID = "uuid";
    public const NAME = "name";
    public const ALTERNATIVE_NAME = "alternativeName";
    public const CODE = "code";
    public const COORDINATES = "coordinates";
    public const RESPONSE_COUNT = "responseCount";

    public const ATTRIBUTES = [self::ID, self::UUID, self::NAME, self::ALTERNATIVE_NAME, self::CODE, self::COORDINATES, self::RESPONSE_COUNT];

    public function getId(): FacilityId;
    public function getName(): string;

    public function getAlternativeName(): null|string;
    public function getCode(): null|string;

    public function getCoordinates(): null|Point;

    public function getResponseCount(): int;
}
