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

    public const ATTRIBUTES = [self::ID, self::NAME];

    public function getDateOfUpdate(): null|\DateTimeInterface;

    public function getId(): ResponseId;

    public function getName(): null|string;

    public function getFacilityTypeLabel(): string;
}
