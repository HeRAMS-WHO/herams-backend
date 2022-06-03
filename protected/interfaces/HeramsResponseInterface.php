<?php

declare(strict_types=1);

namespace prime\interfaces;

use Carbon\Carbon;
use prime\objects\HeramsSubject;

/**
 * TODO Limesurvey deprecation: review what still is needed
 * TODO Limesurvey deprecation: get subjectId should become an int
 */
interface HeramsResponseInterface
{
    public const UNKNOWN_VALUE = '_unknown';

    public const BUCKET75100 = 3;

    public const BUCKET5075 = 2;

    public const BUCKET2550 = 1;

    public const BUCKET25 = 0;

    public function getAccessibility(): string;

    public function getAutoIncrementId(): int;

    public function getCondition(): string;

    public function getDate(): ?Carbon;

    public function getFunctionality(): string;

    public function getId(): int;

    public function getLatitude(): ?float;

    public function getLocation(): ?string;

    public function getLongitude(): ?float;

    public function getMainReason(): ?string;

    public function getName(): ?string;

    public function getType(): ?string;

    public function getRawData(): array;

    public function getSubjectAvailability(): float;

    public function getSubjectAvailabilityBucket(): int;

    public function getSubjectId(): string;

    /**
     * @return HeramsSubject[]
     */
    public function getSubjects(): iterable;

    public function getValueForCode(string $code);
}
