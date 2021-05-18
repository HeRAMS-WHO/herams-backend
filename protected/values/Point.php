<?php
declare(strict_types=1);

namespace prime\values;

class Point extends Geometry
{
    private const DEFAULT_SRID = 4326;
    public function __construct(int|null $srid, private float $x, private float $y)
    {
        parent::__construct($srid);
    }

    public function __toString(): string
    {
        return "({$this->x}, {$this->y})";
    }

    public static function fromString(string $value): self
    {
        if (preg_match("/^\s*\(\s*(?P<x>-?\d*(\.\d*)?)\s*,\s*(?P<y>-?\d*(\.\d*)?)\s*\)$/", $value, $matches)) {
            return  new self(self::DEFAULT_SRID, floatval($matches['x']), floatval($matches['y']));
        }
        throw new \InvalidArgumentException('Argument is not valid WKT');
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }


}
