<?php

declare(strict_types=1);

namespace prime\interfaces;

interface AuditServiceInterface
{
    public function add(NewAuditEntryInterface $entry): void;
    public function commit(): void;
}
