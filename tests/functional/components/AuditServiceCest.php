<?php
declare(strict_types=1);

namespace prime\tests\functional\components;

use Codeception\Stub;
use prime\components\AuditService;
use prime\interfaces\AuditServiceInterface;
use prime\interfaces\NewAuditEntryInterface;
use prime\models\ar\Audit;
use prime\objects\enums\AuditEvent;
use prime\tests\FunctionalTester;
use yii\base\NotSupportedException;
use yii\console\Application as ConsoleApplication;
use yii\web\Application;

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
    public function testBootstrapFailsForNonWebApp(FunctionalTester $I): void
    {
        $I->expectThrowable(NotSupportedException::class, static function () {
            $app = Stub::make(ConsoleApplication::class);
            /** @var AuditService $service */
            $service = \Yii::$app->get('auditService');
            $service->bootstrap($app);
        });
    }

    public function testBootstrapRegistersHandler(FunctionalTester $I): void
    {
        $app = Stub::make(Application::class, [
            'on' => Stub\Expected::once()
        ]);
        /** @var AuditService $service */
        $service = \Yii::$app->get('auditService');
        $service->bootstrap($app);
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

    public function testCommitSilencesErrors(FunctionalTester $I): void
    {
        /** @var AuditService $service */
        $service = \Yii::$app->get('auditService');
        $exception = new \Exception('uh oh');
        \Yii::$app->set('user', new class {
            public $id = 15;
        });
        \Yii::$app->set('db', static function () use ($exception) {
            throw new \Exception('uh oh');
        });
        $I->expectThrowable($exception, function () {
            \Yii::$app->getDb();
        });
        $service->add($this->createEntry());
        $service->commit();
    }
}
