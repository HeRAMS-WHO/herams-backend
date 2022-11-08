<?php

declare(strict_types=1);

namespace herams\common\enums;

enum AuditEvent: string
{
    case Insert = 'insert';
    case Update = 'update';
    case Delete = 'delete';
}
