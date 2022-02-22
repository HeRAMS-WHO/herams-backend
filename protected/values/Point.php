<?php

declare(strict_types=1);

namespace prime\values;

use yii\db\Expression;
use yii\validators\RegularExpressionValidator;

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
        throw new \InvalidArgumentException();
    }


    /**
     * This is Yii2 specific...
     * @param string $attribute
     * @return array
     */
    public static function validatorFor(string $attribute): array
    {
        return [[$attribute], RegularExpressionValidator::class, 'pattern' => "/^\s*\(\s*(-?\d*(\.\d*)?)\s*,\s*(-?\d*(\.\d*)?)\s*\)$/"];
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function toWKT(): Expression
    {
        return new Expression("ST_PointFromText('POINT({$this->getX()} {$this->getY()})', 4326)");
    }
}
