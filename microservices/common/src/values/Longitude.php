<?php

declare(strict_types=1);

namespace herams\common\values;

class Longitude implements \JsonSerializable
{
    public function __construct(
        public readonly float $value
    ) {
        if ($value < -180 || $value > 180) {
            throw new \InvalidArgumentException('Longitude must be between -180 and 180');
        }
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
