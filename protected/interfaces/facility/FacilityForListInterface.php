<?php

declare(strict_types=1);

namespace prime\interfaces\facility;

use prime\values\FacilityId;
use prime\values\Point;

interface FacilityForListInterface
{
    public const ALTERNATIVE_NAME = "alternativeName";
    public const CODE = "code";
    public const ID = "id";
    public const LATITUDE = "latitude";
    public const LONGITUDE = "longitude";
    public const NAME = "name";
    public const RESPONSE_COUNT = "responseCount";

    public const ATTRIBUTES = [self::ID, self::NAME, self::ALTERNATIVE_NAME, self::CODE, self::LATITUDE, self::LONGITUDE, self::RESPONSE_COUNT];

    public function getAlternativeName(): null|string;
    public function getCode(): null|string;
    public function getId(): FacilityId;
    public function getLatitude(): null|float;
    public function getLongitude(): null|float;
    public function getName(): string;
    public function getResponseCount(): int;
}
