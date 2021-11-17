<?php

declare(strict_types=1);

namespace prime\components;

use prime\attributes\TriggersJobWithId;
use prime\helpers\JobQueueProxy;
use prime\interfaces\EventDispatcherInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

class JobSubmissionService
{
    private \Closure $handler;

    public function __construct(
        private JobQueueProxy $jobQueueProxy,
        private EventDispatcherInterface $eventDispatcher
    ) {
        $this->handler = function (Event $event) {
            $reflectionClass = new \ReflectionClass($event->sender);
            /** @var ActiveRecord $record */
            $record = $event->sender;

            foreach ($reflectionClass->getAttributes(TriggersJobWithId::class) as $attribute) {
                /** @var TriggersJobWithId $trigger */
                $trigger = $attribute->newInstance();
                $job = $trigger->create($record->primaryKey);
                $this->jobQueueProxy->get()->putJob($job);
            }
        };
        $this->eventDispatcher->on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, $this->handler);
    }
}
