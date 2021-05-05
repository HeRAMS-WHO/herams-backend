<?php
declare(strict_types=1);

namespace prime\tests\unit\jobs\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\ResponseNotificationJob;
use prime\tests\unit\jobs\JobTest;

/**
 * @covers \prime\jobs\accessRequests\ResponseNotificationJob
 */
class ResponseNotificationJobTest extends JobTest
{
    function createJob(): JobInterface
    {
        return new ResponseNotificationJob(1);
    }
}
