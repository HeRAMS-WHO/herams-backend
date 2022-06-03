<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;
use prime\values\ResponseId;

interface ResponseForList
{
    public const DATE_OF_UPDATE = "dateOfUpdate";

    public const ID = "id";

    public const CONDITION = "condition";

    public const ACCESSIBILITY = "accessibility";

    public const FUNCTIONALITY = "functionality";

    public const ATTRIBUTES = [self::ID, self::DATE_OF_UPDATE, self::CONDITION, self::ACCESSIBILITY, self::FUNCTIONALITY];

    public function getDateOfUpdate(): null|\DateTimeInterface;

    public function getId(): ResponseId;

    public function getCondition(): FacilityCondition;

    public function getAccessibility(): FacilityAccessibility;

    public function getFunctionality(): FacilityFunctionality;
}
