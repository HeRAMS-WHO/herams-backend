<?php

declare(strict_types=1);

namespace prime\models\response;

use Collecthor\DataInterfaces\RecordInterface;
use prime\interfaces\AdminResponseForListInterface;
use prime\interfaces\HeramsResponseInterface;
use prime\values\ResponseId;

class AdminResponseForList implements AdminResponseForListInterface, RecordInterface
{
    public function __construct(private HeramsResponseInterface & RecordInterface $surveyResponse)
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

    public function getData(): array
    {
        return $this->surveyResponse->getRawData();
    }

    public function getDataValue(array $path): string|int|float|bool|null|array
    {
        return $this->surveyResponse->getDataValue($path);
    }

    public function allData(): array
    {
        return $this->surveyResponse->allData();
    }
}
