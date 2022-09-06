<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;
use prime\values\ResponseId;

interface AdminResponseForListInterface
{
    public const ID = "id";

    public const NAME = "name";

    public const DATE_OF_UPDATE = 'dateOfUpdate';

    public const ATTRIBUTES = [self::ID, self::NAME, self::DATE_OF_UPDATE];

    public function getDateOfUpdate(): null|\DateTimeInterface;

    public function getId(): ResponseId;

    public function getName(): null|string;
}
