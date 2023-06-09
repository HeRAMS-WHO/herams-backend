<?php

declare(strict_types=1);

namespace herams\common\attributes;

use Attribute;
use herams\common\models\ActiveRecord;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Audits
{
    public function __construct(
        private string $event
    ) {
    }

    public function auditInsert(): bool
    {
        return $this->event === ActiveRecord::EVENT_AFTER_INSERT;
    }

    public function auditUpdate(): bool
    {
        return $this->event === ActiveRecord::EVENT_AFTER_UPDATE;
    }

    public function auditDelete(): bool
    {
        return $this->event === ActiveRecord::EVENT_AFTER_DELETE;
    }
}
