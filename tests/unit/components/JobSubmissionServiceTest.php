<?php

declare(strict_types=1);

namespace prime\tests\unit\components;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\components\JobSubmissionService;
use prime\helpers\EventDispatcher;
use prime\helpers\JobQueueProxy;
use prime\models\ar\AccessRequest;
use yii\db\AfterSaveEvent;

/**
 * @covers \prime\components\JobSubmissionService
 */
class JobSubmissionServiceTest extends Unit
{
    public function testJobIsSubmitted()
    {
        // Setup

        $jobQueue = $this->getMockBuilder(JobQueueInterface::class)
            ->getMock();

        $jobQueue->expects($this->once())
            ->method('putJob');
        $dispatcher = new EventDispatcher();

        $subject = new JobSubmissionService($jobQueue, $dispatcher);

        // Class
        $accessRequest = new AccessRequest();
        $accessRequest->id = 13;
        $event = new AfterSaveEvent();
        $event->sender = $accessRequest;
        $dispatcher->trigger(AccessRequest::class, AccessRequest::EVENT_AFTER_INSERT, $event);
    }
}
