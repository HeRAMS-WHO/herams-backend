<?php
declare(strict_types=1);

namespace prime\values;

use yii\db\Expression;

class Geometry
{
    public function __construct(private int|null $srid)
    {
    }

    public static function fromParsedArray(array $data): self
    {
        return match ($data['type']) {
            "POINT" => new Point($data['srid'], $data['value'][1], $data['value'][0])
        };
    }

    public function toWKT(): Expression
    {
        return match (static::class) {
            Point::class => new Expression("ST_PointFromText('POINT({$this->getX()} {$this->getY()})', 4326)")
        };
    }
}
