<?php

declare(strict_types=1);

namespace prime\tests\helpers;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\helpers\JobQueueProxy;

/**
 * @covers \prime\helpers\JobQueueProxy
 */
final class JobQueueProxyTest extends \Codeception\Test\Unit
{
    public function test(): void
    {
        #create stub for JobQueueProxy class
        $jobQueue = $this->getMockBuilder(JobQueueProxy::class)
            ->disableOriginalConstructor()
            ->getMock();

        #create stub for JobQueueProxyInterface class
        $jobQueueInterface = $this->getMockBuilder(JobQueueInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /*
        #configure the stub
        $this->jobQueue->expects($this->once())
            ->method('get')
            ->willReturn($this->jobQueueInterface);

        $this->assertSame($this->jobQueue, $this->jobQueue->get());

        */

        $jobQueue = new JobQueueProxy($jobQueueInterface);

        $this->assertSame($jobQueueInterface, $jobQueue->get());
    }
}
