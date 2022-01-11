<?php

declare(strict_types=1);

namespace prime\components;

use Carbon\Carbon;
use prime\attributes\Audits;
use prime\interfaces\AuditServiceInterface;
use prime\interfaces\EventDispatcherInterface;
use prime\interfaces\NewAuditEntryInterface;
use prime\objects\enums\AuditEvent;
use prime\objects\NewAuditEntry;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\Connection;
use yii\web\Application;
use yii\web\User;

class AuditService implements BootstrapInterface, AuditServiceInterface
{
    public string $table = '{{%audit}}';
    /**
     * @var array<NewAuditEntryInterface, int>
     */
    private array $entries = [];

    public bool $enabled = true;

    private array $handlers = [];

    /**
     * @todo When we figure out component interdependencies this should become a constructor argument
     */
    private Connection $db;

    /**
     * @todo When we figure out component interdependencies this should become a constructor argument
     */
    private User $user;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher->on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, function (AfterSaveEvent $event) {
            $reflectionClass = new \ReflectionClass($event->sender);
            foreach ($reflectionClass->getAttributes(Audits::class) as $attribute) {
                /** @var Audits $audit */
                $audit = $attribute->newInstance();
                if ($audit->auditInsert()) {
                    $this->add(NewAuditEntry::fromActiveRecord($event->sender, AuditEvent::insert()));
                    return;
                }
            }
        });

        $this->eventDispatcher->on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_UPDATE, function (AfterSaveEvent $event) {
            $reflectionClass = new \ReflectionClass($event->sender);
            foreach ($reflectionClass->getAttributes(Audits::class) as $attribute) {
                /** @var Audits $audit */
                $audit = $attribute->newInstance();
                if ($audit->auditUpdate()) {
                    $this->add(NewAuditEntry::fromActiveRecord($event->sender, AuditEvent::update()));
                    return;
                }
            }
        });

        $this->eventDispatcher->on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_DELETE, function (Event $event) {
            $reflectionClass = new \ReflectionClass($event->sender);
            foreach ($reflectionClass->getAttributes(Audits::class) as $attribute) {
                /** @var Audits $audit */
                $audit = $attribute->newInstance();
                if ($audit->auditDelete()) {
                    $this->add(NewAuditEntry::fromActiveRecord($event->sender, AuditEvent::delete()));
                    return;
                }
            }
        });
    }

    public function add(NewAuditEntryInterface $entry): void
    {
        if ($this->enabled) {
            $this->entries[] = [
                $entry,
                $this->getUserId(),
            ];
        }
    }

    private function getUserId(): int
    {
        return $this->user->getId();
    }


    public function bootstrap($app): void
    {
        if (!$app instanceof Application) {
            throw new NotSupportedException('This service only supports web application');
        }
        $this->db = $app->getDb();
        $this->user = $app->getUser();

        $app->on(Application::EVENT_AFTER_REQUEST, fn() => $this->commit());
    }

    public function commit(): void
    {
        if (empty($this->entries)) {
            return;
        }

        $rows = [];
        $timestamp = Carbon::now();
        /**
         * @var NewAuditEntryInterface $entry
         */
        foreach ($this->entries as [$entry, $userId]) {
            $rows[] = [
                $entry->getSubjectName(),
                $entry->getSubjectId(),
                $entry->getEvent(),
                $timestamp,
                $userId
            ];
        }

        try {
            $this->db->createCommand()->batchInsert(
                $this->table,
                ['subject_name', 'subject_id', 'event', 'created_at', 'created_by'],
                $rows
            )->execute();
        } catch (\Throwable $e) {
            \Yii::error($rows, 'auditservice.uncommitted');
            \Yii::error($e);
        }
        $this->entries = [];
    }
}
