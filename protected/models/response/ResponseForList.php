<?php

declare(strict_types=1);

namespace prime\models\response;

use prime\interfaces\HeramsResponseInterface;
use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;
use prime\values\ResponseId;

class ResponseForList implements \prime\interfaces\ResponseForList
{
    public function __construct(private HeramsResponseInterface $response)
    {
    }

    public function getExternalId()
    {
        return $this->response->getId();
    }

    public function getId(): ResponseId
    {
        return new ResponseId($this->response->getAutoIncrementId());
    }

    public function getCondition(): FacilityCondition
    {
        return FacilityCondition::tryFrom($this->response->getCondition()) ?? FacilityCondition::Unknown;
    }

    public function getAccessibility(): FacilityAccessibility
    {
        return FacilityAccessibility::tryFrom($this->response->getAccessibility()) ?? FacilityAccessibility::Unknown;
    }

    public function getFunctionality(): FacilityFunctionality
    {
        codecept_debug($this->response->getFunctionality());
        return FacilityFunctionality::tryFrom($this->response->getFunctionality()) ?? FacilityFunctionality::Unknown;
    }

    public function getDateOfUpdate(): null|\DateTimeInterface
    {
        return $this->response->getDate();
    }
}
