<?php

declare(strict_types=1);

namespace prime\values;

use CrEOF\Geo\WKB\Parser;
use prime\interfaces\Dehydrator;
use prime\interfaces\Hydrator;
use yii\base\NotSupportedException;
use yii\db\Expression;

class Geometry implements Hydrator, Dehydrator
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

    public static function fromDatabase(float|int|string|null $value): null|static
    {
        if (!is_string($value)) {
            return null;
        }
        $parser = new Parser();
        $data = $parser->parse(substr($value, 4));
        $data['srid'] = unpack('i', $value)[1];
        return Geometry::fromParsedArray($data);
    }

    public static function fromForm(null|string $value): null|static
    {
        if (empty($value)) {
            return null;
        }

        return match (static::class) {
            Point::class => Point::fromString($value)
        };
    }

    public function toWKT(): Expression
    {
        throw new NotSupportedException();
    }
}
