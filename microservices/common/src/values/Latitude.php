<?php

declare(strict_types=1);

namespace herams\common\values;

class Latitude implements \JsonSerializable
{
    public function __construct(
        public readonly float $value
    ) {
        if ($value < -90 || $value > 90) {
            throw new \InvalidArgumentException('Latitude must be between -90 and 90');
        }
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
