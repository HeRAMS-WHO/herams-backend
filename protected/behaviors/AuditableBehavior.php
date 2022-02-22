<?php

declare(strict_types=1);

namespace prime\behaviors;

use prime\interfaces\AuditServiceInterface;
use prime\objects\enums\AuditEvent;
use prime\objects\NewAuditEntry;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

class AuditableBehavior extends Behavior
{
    public bool $auditUpdate = true;
    public bool $auditCreate = true;
    public bool $auditDelete = true;

    public bool $auditEmptyUpdates = false;

    public function __construct(private AuditServiceInterface $auditService, array $config = [])
    {
        parent::__construct($config);
    }

    public function attach($owner): void
    {
        if (!$owner instanceof ActiveRecord) {
            throw new \InvalidArgumentException('Behavior can only be attached to ActiveRecord instances');
        }
        parent::attach($owner);
    }


    private function getService(): AuditServiceInterface
    {
        return $this->auditService;
    }

    private function addEntryForEvent(AuditEvent $event): void
    {
        $this->getService()->add(NewAuditEntry::fromActiveRecord($this->owner, $event));
    }

    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => function (AfterSaveEvent $event): void {
                if ($this->auditCreate) {
                    $this->addEntryForEvent(AuditEvent::insert());
                }
            },
            BaseActiveRecord::EVENT_AFTER_UPDATE => function (AfterSaveEvent $event): void {
                if ($this->auditUpdate && (!empty($event->changedAttributes) || $this->auditEmptyUpdates)) {
                    $this->addEntryForEvent(AuditEvent::update());
                }
            },
            BaseActiveRecord::EVENT_AFTER_DELETE => function (): void {
                if ($this->auditDelete) {
                    $this->addEntryForEvent(AuditEvent::delete());
                }
            }
        ];
    }
}
