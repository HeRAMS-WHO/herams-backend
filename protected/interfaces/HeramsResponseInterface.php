<?php


namespace prime\interfaces;


use Carbon\Carbon;
use prime\objects\HeramsSubject;

interface HeramsResponseInterface
{
    public const UNKNOWN_VALUE = '_unknown';
    public const BUCKET75100 = 3;
    public const BUCKET5075 = 2;
    public const BUCKET2550 = 1;
    public const BUCKET25 = 0;
    public function getLatitude(): ?float;
    public function getLongitude(): ?float;

    public function getId(): int;

    public function getType(): ?string;

    public function getName(): ?string;


    public function getValueForCode(string $code);

    public function getSubjectId(): string;

    public function getLocation(): ?string;

    public function getDate(): ?Carbon;

    /**
     * @return HeramsSubject[]
     */
    public function getSubjects(): iterable;

    public function getSubjectAvailability(): float;

    public function getSubjectAvailabilityBucket(): int;

    public function getFunctionality(): string;

    public function getMainReason(): ?string;

    public function getRawData(): array;
}