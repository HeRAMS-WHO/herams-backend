<?php
declare(strict_types=1);

namespace prime\tests\unit\jobs;

use Codeception\Test\Unit;
use JCIT\jobqueue\interfaces\JobInterface;

/**
 * @coversNothing
 */
abstract class JobTest extends Unit
{
    abstract function createJob(): JobInterface;

    public function testSerialization()
    {
        $job = $this->createJob();
        $serialized = $job->jsonSerialize();
        $this->assertEquals($job, $job::fromArray($serialized));
    }
}
