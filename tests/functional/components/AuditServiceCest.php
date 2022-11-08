<?php

declare(strict_types=1);

namespace prime\tests\functional\components;

use herams\common\components\AuditService;
use herams\common\enums\AuditEvent;
use herams\common\interfaces\AuditServiceInterface;
use herams\common\interfaces\NewAuditEntryInterface;
use prime\helpers\EventDispatcher;
use prime\models\ar\AccessRequest;
use prime\models\ar\Audit;
use prime\tests\FunctionalTester;
use yii\db\AfterSaveEvent;

/**
 * @covers \herams\common\components\AuditService
 */
class AuditServiceCest
{
    private function createEntry(): NewAuditEntryInterface
    {
        return new class() implements NewAuditEntryInterface {
            public function getSubjectName(): string
            {
                return 'testname';
            }

            public function getSubjectId(): int
            {
                return 123;
            }

            public function getEvent(): AuditEvent
            {
                return AuditEvent::Insert;
            }
        };
    }

    public function testCommitWithoutEntriesDoesNothing(FunctionalTester $I): void
    {
        $service = \Yii::$app->get('auditService');
        $I->assertInstanceOf(AuditServiceInterface::class, $service);
        $service->commit();
    }

    public function testCommit(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        /** @var AuditService $service */
        $service = \Yii::$app->get('auditService');

        $entry = $this->createEntry();
        $service->add($entry);
        $I->assertSame(0, (int) Audit::find()->count());
        $service->commit();
        $I->assertSame(1, (int) Audit::find()->count());
        $model = Audit::find()->one();
        $I->assertSame($entry->getSubjectName(), $model->subject_name);
        $I->assertSame($entry->getSubjectId(), $model->subject_id);
        $I->assertSame($entry->getEvent()->value, $model->event);
    }

    public function testUpdate(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $eventDispatcher = new EventDispatcher();
        $service = new AuditService($eventDispatcher);
        $service->bootstrap(\Yii::$app);

        // Class
        $accessRequest = new AccessRequest();
        $accessRequest->id = mt_rand(1, 100000);
        $event = new AfterSaveEvent();
        $event->sender = $accessRequest;
        $eventDispatcher->trigger(AccessRequest::class, AccessRequest::EVENT_AFTER_UPDATE, $event);

        $I->assertSame(0, (int) Audit::find()->count());
        $service->commit();
        $I->assertSame(1, (int) Audit::find()->count());

        $audit = Audit::find()->one();
        $I->assertSame($accessRequest->id, $audit->subject_id);
        $I->assertSame(AuditEvent::Update->value, $audit->event);
    }

    public function testDelete(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $eventDispatcher = new EventDispatcher();
        $service = new AuditService($eventDispatcher);
        $service->bootstrap(\Yii::$app);

        // Class
        $accessRequest = new AccessRequest();
        $accessRequest->id = mt_rand(1, 100000);
        $event = new AfterSaveEvent();
        $event->sender = $accessRequest;
        $eventDispatcher->trigger(AccessRequest::class, AccessRequest::EVENT_AFTER_DELETE, $event);

        $I->assertSame(0, (int) Audit::find()->count());
        $service->commit();
        $I->assertSame(1, (int) Audit::find()->count());

        $audit = Audit::find()->one();
        $I->assertSame($accessRequest->id, $audit->subject_id);
        $I->assertSame(AuditEvent::Delete->value, $audit->event);
    }

    public function testInsert(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $eventDispatcher = new EventDispatcher();
        $service = new AuditService($eventDispatcher);
        $service->bootstrap(\Yii::$app);

        // Class
        $accessRequest = new AccessRequest();
        $accessRequest->id = mt_rand(1, 100000);
        $event = new AfterSaveEvent();
        $event->sender = $accessRequest;
        $eventDispatcher->trigger(AccessRequest::class, AccessRequest::EVENT_AFTER_INSERT, $event);

        $I->assertSame(0, (int) Audit::find()->count());
        $service->commit();
        $I->assertSame(1, (int) Audit::find()->count());

        $audit = Audit::find()->one();
        $I->assertSame($accessRequest->id, $audit->subject_id);
        $I->assertSame(AuditEvent::Insert->value, $audit->event);
    }
}
