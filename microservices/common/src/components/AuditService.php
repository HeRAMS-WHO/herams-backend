<?php

declare(strict_types=1);

namespace herams\common\components;

use Carbon\Carbon;
use herams\common\attributes\Audits;
use herams\common\enums\AuditEvent;
use herams\common\helpers\NewAuditEntry;
use herams\common\interfaces\AuditServiceInterface;
use herams\common\interfaces\CommandFactoryInterface;
use herams\common\interfaces\CurrentUserIdProviderInterface;
use herams\common\interfaces\EventDispatcherInterface;
use herams\common\interfaces\NewAuditEntryInterface;
use herams\common\values\UserId;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\Connection;
use yii\web\Application;

final class AuditService implements BootstrapInterface, AuditServiceInterface
{
    public string $table = '{{%audit}}';

    /**
     * @var list<array{0: NewAuditEntryInterface, 1: UserId}>
     */
    private array $entries = [];
    public function __construct(
        private readonly CommandFactoryInterface $commandFactory,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CurrentUserIdProviderInterface $userIdProvider
    ) {
        $this->eventDispatcher->on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, function (AfterSaveEvent $event) {
            $reflectionClass = new \ReflectionClass($event->sender);
            foreach ($reflectionClass->getAttributes(Audits::class) as $attribute) {
                /** @var Audits $audit */
                $audit = $attribute->newInstance();
                if ($audit->auditInsert()) {
                    $this->add(NewAuditEntry::fromActiveRecord($event->sender, AuditEvent::Insert));
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
                    $this->add(NewAuditEntry::fromActiveRecord($event->sender, AuditEvent::Update));
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
                    $this->add(NewAuditEntry::fromActiveRecord($event->sender, AuditEvent::Delete));
                    return;
                }
            }
        });
    }

    public function add(NewAuditEntryInterface $entry): void
    {
        $this->entries[] = [
            $entry,
            $this->userIdProvider->getUserId(),
        ];
    }
    public function bootstrap($app): void
    {
        if (! $app instanceof Application) {
            throw new NotSupportedException('This service only supports web applications');
        }
        $app->on(Application::EVENT_AFTER_REQUEST, fn () => $this->commit());
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
         * @var UserId $userId
         */
        foreach ($this->entries as [$entry, $userId]) {
            $rows[] = [
                $entry->getSubjectName(),
                $entry->getSubjectId(),
                $entry->getEvent()->value,
                $timestamp,
                $userId->getValue(),
            ];
        }


        try {
            $this->commandFactory->createCommand()->batchInsert(
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
