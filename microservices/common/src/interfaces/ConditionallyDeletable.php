<?php

declare(strict_types=1);

namespace herams\common\interfaces;

interface ConditionallyDeletable
{
    public function canBeDeleted(): bool;
}
