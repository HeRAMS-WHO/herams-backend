<?php
declare(strict_types=1);

namespace prime\models\response;

use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Response;
use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;
use prime\values\ResponseId;

class ResponseForList implements \prime\interfaces\ResponseForList
{
    public function __construct(private HeramsResponseInterface $response)
    {
    }

    public function getId(): ResponseId
    {
        return new ResponseId($this->response->getId());
    }

    public function getCondition(): FacilityCondition
    {
        return FacilityCondition::unknown();
    }

    public function getAccessibility(): FacilityAccessibility
    {
        return FacilityAccessibility::unknown();
    }

    public function getFunctionality(): FacilityFunctionality
    {
        return FacilityFunctionality::unknown();
    }

    public function getDateOfUpdate(): null|\DateTimeInterface
    {
        return $this->response->getDate();
    }
}
