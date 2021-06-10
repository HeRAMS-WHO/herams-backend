<?php
declare(strict_types=1);

namespace prime\interfaces;

use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;
use prime\values\ResponseId;

interface ResponseForList
{

    public function getDateOfUpdate(): null|\DateTimeInterface;

    public function getId(): ResponseId;

    public function getCondition(): FacilityCondition;

    public function getAccessibility(): FacilityAccessibility;

    public function getFunctionality(): FacilityFunctionality;
}
