<?php

declare(strict_types=1);

namespace prime\interfaces;

interface LabeledEnum
{
    public function label(): string;
}
