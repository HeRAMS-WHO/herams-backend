<?php
declare(strict_types=1);

namespace prime\tests\unit\behaviors;

use prime\behaviors\AuditableBehavior;
use prime\interfaces\AuditServiceInterface;
use yii\base\Component;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

/**
 * @covers \prime\behaviors\AuditableBehavior
 */
class AuditableBehaviorTest extends \Codeception\Test\Unit
{
    public function testNonActiveRecordOwner(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();
        $auditableBehavior = new AuditableBehavior($auditService);
        $auditableBehavior->attach(new Component());
    }

    public function testInsertCallsService(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->once())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $model->trigger(ActiveRecord::EVENT_AFTER_INSERT, new AfterSaveEvent());
    }

    public function testInsertDoesNotCallService(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->never())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);
        $auditableBehavior->auditCreate = false;

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $model->trigger(ActiveRecord::EVENT_AFTER_INSERT, new AfterSaveEvent());
    }

    public function testUpdateCallsService(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->once())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $event = new AfterSaveEvent();
        $event->changedAttributes = ['a' => 5];
        $model->trigger(ActiveRecord::EVENT_AFTER_UPDATE, $event);
    }

    public function testUpdateDoesNotCallServiceWithEmptyAttributes(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->never())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $event = new AfterSaveEvent();
        $event->changedAttributes = [];
        $model->trigger(ActiveRecord::EVENT_AFTER_UPDATE, $event);
    }

    public function testUpdateCallsServiceWithEmptyAttributes(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->once())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);
        $auditableBehavior->auditEmptyUpdates = true;

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $event = new AfterSaveEvent();
        $event->changedAttributes = [];
        $model->trigger(ActiveRecord::EVENT_AFTER_UPDATE, $event);
    }

    public function testUpdateDoesNotCallService(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->never())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);
        $auditableBehavior->auditUpdate = false;

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $event = new AfterSaveEvent();
        $event->changedAttributes = ['a' => 5];
        $model->trigger(ActiveRecord::EVENT_AFTER_UPDATE, $event);
    }

    public function testDeleteCallsService(): void
    {
        $auditService = $this
            ->getMockBuilder(AuditServiceInterface::class)
            ->getMock();

        $auditService->expects($this->once())->method('add');

        $auditableBehavior = new AuditableBehavior($auditService);
        $auditableBehavior->auditUpdate = false;

        $model = new class extends ActiveRecord {
            public $primaryKey = 15;
        };

        $model->attachBehavior('audit', $auditableBehavior);
        $event = new AfterSaveEvent();
        $model->trigger(ActiveRecord::EVENT_AFTER_DELETE, $event);
    }
}
