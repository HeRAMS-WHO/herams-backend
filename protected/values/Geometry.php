<?php
declare(strict_types=1);

namespace prime\values;


class Geometry
{
    public function __construct(private int|null $srid)
    {
    }

    public static function fromParsedArray(array $data): self
    {
        switch($data['type']) {
            case "POINT":
                return new Point($data['srid'], $data['value'][0], $data['value'][1]);

        }

    }

}
