<?php

declare(strict_types=1);

namespace prime\tests\functional\components;

use Codeception\Stub;
use prime\components\AuditService;
use prime\helpers\EventDispatcher;
use prime\interfaces\AuditServiceInterface;
use prime\interfaces\NewAuditEntryInterface;
use prime\models\ar\AccessRequest;
use prime\models\ar\Audit;
use prime\objects\enums\AuditEvent;
use prime\tests\FunctionalTester;
use yii\base\NotSupportedException;
use yii\console\Application as ConsoleApplication;
use yii\db\AfterSaveEvent;
use yii\db\Connection;
use yii\web\Application;
use yii\web\User;

/**
 * @covers \prime\components\AuditService
 */
class AuditServiceCest
{
    private function createEntry(): NewAuditEntryInterface
    {
        return new class implements NewAuditEntryInterface {

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
                return AuditEvent::insert();
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
        $I->assertSame(AuditEvent::update()->value, $audit->event);
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
        $I->assertSame(AuditEvent::delete()->value, $audit->event);
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
        $I->assertSame(AuditEvent::insert()->value, $audit->event);
    }
}
