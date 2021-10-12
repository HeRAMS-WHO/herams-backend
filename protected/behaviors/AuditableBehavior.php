<?php
declare(strict_types=1);

namespace prime\behaviors;

use prime\components\AuditService;
use prime\objects\enums\AuditEvent;
use prime\objects\NewAuditEntry;
use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

class AuditableBehavior extends Behavior
{
    public bool $auditUpdate = true;
    public bool $auditCreate = true;
    public bool $auditDelete = true;

    public bool $auditEmptyUpdates = false;

    private function getService(): AuditService
    {
        return \Yii::$app->get('auditService');
    }

    private function addEntryForEvent(AuditEvent $event)
    {
        $this->getService()->add(NewAuditEntry::fromActiveRecord($this->owner, $event));
    }

    public function events()
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
                $this->addEntryForEvent(AuditEvent::delete());
            }
        ];
    }
}
