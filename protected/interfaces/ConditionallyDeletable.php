<?php

declare(strict_types=1);

namespace prime\interfaces;

interface ConditionallyDeletable
{
    public function canBeDeleted(): bool;
}
