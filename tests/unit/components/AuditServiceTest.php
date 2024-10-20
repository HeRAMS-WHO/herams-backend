<?php

declare(strict_types=1);

namespace prime\tests\unit\components;

use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use herams\common\components\AuditService;
use herams\common\enums\AuditEvent;
use herams\common\interfaces\NewAuditEntryInterface;
use prime\helpers\EventDispatcher;
use yii\base\NotSupportedException;
use yii\console\Application as ConsoleApplication;
use yii\db\Connection;
use yii\web\Application;
use yii\web\User;

/**
 * @covers \herams\common\components\AuditServic
 * @skip
 */
class AuditServiceTest extends Unit
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

    public function testCommitSilencesErrors(): void
    {
        $db = $this->makeEmpty(Connection::class, [
            'createCommand' => Expected::atLeastOnce(fn () => throw new \Exception()),
        ]);
        $app = $this->make(Application::class, [
            'on' => Expected::atLeastOnce(),
            'getDb' => $db,
            'getUser' => Stub::make(User::class, [
                'getId' => 15,
            ]),
        ]);

        $service = new AuditService(new EventDispatcher());
        $service->bootstrap($app);

        $service->add($this->createEntry());
        $service->commit();
    }

    public function testBootstrapFailsForNonWebApp(): void
    {
        $this->expectException(NotSupportedException::class);
        $app = $this->make(ConsoleApplication::class);
        $service = new AuditService(new EventDispatcher());
        $service->bootstrap($app);
    }

    public function testBootstrapRegistersHandler(): void
    {
        $app = $this->make(Application::class, [
            'on' => Expected::atLeastOnce(),
            'getDb' => Stub::makeEmpty(Connection::class),
            'getUser' => Stub::makeEmpty(User::class),
        ]);
        $service = new AuditService(new EventDispatcher());
        $service->bootstrap($app);
    }
}
