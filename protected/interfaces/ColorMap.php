<?php

declare(strict_types=1);

namespace prime\interfaces;

interface ColorMap
{
    /**
     * @param string $index The index
     * @return string The color in hex format: #FF00FF, or null if not set for this index
     */
    public function getColor(string $index): null|string;
}
