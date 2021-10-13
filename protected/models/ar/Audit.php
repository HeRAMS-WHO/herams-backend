<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\models\ActiveRecord;
use prime\queries\AuditQuery;

class Audit extends ActiveRecord
{
    public static function find(): AuditQuery
    {
        return new AuditQuery(static::class);
    }
}
