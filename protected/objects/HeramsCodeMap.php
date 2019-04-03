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
        return 'MoSDGPS[SQ001]';
    }

    public function getLongitude(): string
    {
        return 'MoSDGPS[SQ002]';
    }

    public function getType(): string
    {
        return 'MoSD3';
    }

    public function getName(): string
    {
        return 'MoSD2';
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
        return 'GEO1';
    }

    public function getFunctionality(): string
    {
        return 'HFFUNCT';
    }
}