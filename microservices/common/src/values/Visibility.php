<?php

declare(strict_types=1);

namespace herams\common\values;

use herams\common\enums\ProjectVisibility;

class Visibility
{
    public function __construct(
        public readonly string $value
    ) {
        $found = false;
        foreach (ProjectVisibility::cases() as $case) {
            if ($case->value === $this->value) {
                $found = true;
            }
        }
        if (! $found) {
            throw new \InvalidArgumentException('Visibility should be a ProjectVisibility enum');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
