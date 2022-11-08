<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\enums\AuditEvent;

interface NewAuditEntryInterface
{
    public function getSubjectName(): string;

    public function getSubjectId(): int;

    public function getEvent(): AuditEvent;
}
