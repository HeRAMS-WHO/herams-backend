<?php


namespace prime\objects;


class FacilityType
{

    public const SECONDARY = 'secondary';
    public const PRIMARY = 'primary';
    public const OTHER = 'other';

    private $value;

    public function __construct(string $type)
    {
        if (!in_array($type, [
            self::SECONDARY,
            self::PRIMARY,
            self::OTHER
        ])) {
            throw new \InvalidArgumentException('Unknown type: ' . $type);
        }
        $this->value = $type;

    }

}