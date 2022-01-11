<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\objects\enums\AuditEvent;

interface NewAuditEntryInterface
{
    public function getSubjectName(): string;
    public function getSubjectId(): int;
    public function getEvent(): AuditEvent;
}
