<?php
declare(strict_types = 1);

namespace prime\objects;

/**
 * Class HeramsCodeMap
 * Maps question codes in HeRAMS projects.
 * @package prime\objects
 */
class HeramsCodeMap
{

    public function getLatitude(): string
    {
        return 'GPS[SQ001]';
    }

    public function getLongitude(): string
    {
        return 'GPS[SQ002]';
    }

    public function getType(): string
    {
        return 'HF2';
    }

    public function getName(): string
    {
        return 'HF1';
    }

    public function getDate(): string
    {
        return 'Update';
    }

    public function getSubjectId(): string
    {
        return 'UOID';
    }

    public function getLocation(): string
    {
        return 'GEO2';
    }
}