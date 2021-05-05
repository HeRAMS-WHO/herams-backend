<?php
declare(strict_types=1);

namespace prime\tests\unit\jobs\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \prime\jobs\accessRequests\CreatedNotificationJob
 * @covers \prime\jobs\accessRequests\AccessRequestJob
 */
class CreatedNotificationJobTest extends JobTest
{
    function createJob(): JobInterface
    {
        return new CreatedNotificationJob(1);
    }
}
