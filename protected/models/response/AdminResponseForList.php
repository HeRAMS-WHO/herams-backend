<?php

declare(strict_types=1);

namespace prime\models\response;

use prime\interfaces\AdminResponseForListInterface;
use prime\interfaces\HeramsResponseInterface;
use prime\values\ResponseId;

class AdminResponseForList implements AdminResponseForListInterface
{
    public function __construct(private HeramsResponseInterface $surveyResponse)
    {
    }

    public function getDateOfUpdate(): null|\DateTimeInterface
    {
        return $this->surveyResponse->getDate();
    }

    public function getId(): ResponseId
    {
        return new ResponseId($this->surveyResponse->getId());
    }

    public function getName(): null|string
    {
        return 'todo';
    }

    public function getFacilityTypeLabel(): string
    {
        $this->surveyResponse->getType();
    }
}
