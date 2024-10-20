<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\enums\AuditEvent;
use herams\common\interfaces\NewAuditEntryInterface;
use yii\db\ActiveRecord;

class NewAuditEntry implements NewAuditEntryInterface
{
    private string $subjectName;

    private int $subjectId;

    private AuditEvent $event;

    private function __construct()
    {
    }

    public static function fromActiveRecord(ActiveRecord $model, AuditEvent $event): self
    {
        $result = new self();
        $result->subjectName = get_class($model);
        $result->subjectId = $model->primaryKey;
        $result->event = $event;
        return $result;
    }

    public function getSubjectName(): string
    {
        return $this->subjectName;
    }

    public function getSubjectId(): int
    {
        return $this->subjectId;
    }

    public function getEvent(): AuditEvent
    {
        return $this->event;
    }
}
