<?php

declare(strict_types=1);

namespace prime\helpers;

class ColorMap implements \prime\interfaces\ColorMap
{
    private array $dictionary = [];
    public function __construct(array $dictionary)
    {
        foreach ($dictionary as $key => $value) {
            if (!preg_match('/^#[a-f0-9]{6}$/i', $value)) {
                throw new \InvalidArgumentException("Color must be passed in hex notation");
            }
            $this->dictionary[(string) $key] = strtolower($value);
        }
    }

    public function getColor(string $index): null|string
    {
        return $this->dictionary[$index] ?? null;
    }
}
