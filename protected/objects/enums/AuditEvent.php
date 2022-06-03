<?php

declare(strict_types=1);

namespace prime\objects\enums;

enum AuditEvent: string
{
    case Insert = 'insert';
    case Update = 'update';
    case Delete = 'delete';
}
