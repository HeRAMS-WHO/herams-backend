<?php

declare(strict_types=1);

namespace prime\helpers;

use herams\common\interfaces\AuditServiceInterface;
use herams\common\interfaces\NewAuditEntryInterface;

class AuditServiceProxy implements AuditServiceInterface
{
    private function getService(): AuditServiceInterface
    {
        return \Yii::$app->get('auditService');
    }

    public function add(NewAuditEntryInterface $entry): void
    {
        $this->getService()->add($entry);
    }

    public function commit(): void
    {
        $this->getService()->commit();
    }
}
