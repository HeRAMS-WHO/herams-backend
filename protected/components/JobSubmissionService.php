<?php

declare(strict_types=1);

namespace prime\components;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\attributes\TriggersJob;
use prime\interfaces\EventDispatcherInterface;
use prime\interfaces\QueueJobFromModelInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

class JobSubmissionService
{
    private \Closure $handler;

    public function __construct(
        private JobQueueInterface $jobQueue,
        private EventDispatcherInterface $eventDispatcher
    ) {
        $this->handler = function (Event $event) {
            $reflectionClass = new \ReflectionClass($event->sender);
            /** @var ActiveRecord $record */
            $record = $event->sender;

            foreach ($reflectionClass->getAttributes(QueueJobFromModelInterface::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
                /** @var QueueJobFromModelInterface $trigger */
                $trigger = $attribute->newInstance();
                $job = $trigger->create($record);
                $this->jobQueue->putJob($job);
            }
        };
        $this->eventDispatcher->on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, $this->handler);
    }
}
