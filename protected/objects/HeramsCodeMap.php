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

    public function getCondition(): string
    {
        return 'CONDB';
    }

    public function getAcessibility(): string
    {
        return 'HFACC';
    }

    public function getSubjectExpression(): string
    {
        return '/^QHeRAMS\d+$/';
    }
}
