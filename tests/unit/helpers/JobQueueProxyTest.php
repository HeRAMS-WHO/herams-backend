<?php

declare(strict_types=1);

namespace prime\tests\helpers;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\helpers\JobQueueProxy;

/**
 * @covers \prime\helpers\JobQueueProxy
 */
class JobQueueProxyTest extends \Codeception\Test\Unit
{
    private JobQueueInterface $jobQueueInterface;
    private JobQueueProxy $jobQueue;

    public function test()
    {
        #create stub for JobQueueProxy class
        $this->jobQueue = $this->getMockBuilder(JobQueueProxy::class)
            ->disableOriginalConstructor()
            ->getMock();

        #create stub for JobQueueProxyInterface class
        $this->jobQueueInterface = $this->getMockBuilder(JobQueueInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /*
        #configure the stub
        $this->jobQueue->expects($this->once())
            ->method('get')
            ->willReturn($this->jobQueueInterface);

        $this->assertSame($this->jobQueue, $this->jobQueue->get());

        */

        $this->jobQueue = new JobQueueProxy($this->jobQueueInterface);

        $this->assertSame($this->jobQueueInterface, $this->jobQueue->get());
    }
}
