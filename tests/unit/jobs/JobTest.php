<?php

declare(strict_types=1);

namespace prime\tests\unit\jobs;

use Codeception\Test\Unit;
use JCIT\jobqueue\interfaces\JobInterface;

abstract class JobTest extends Unit
{
    abstract public function createJob(): JobInterface;

    public function testSerialization()
    {
        $job = $this->createJob();
        $serialized = $job->jsonSerialize();
        $this->assertEquals($job, $job::fromArray($serialized));
    }
}
